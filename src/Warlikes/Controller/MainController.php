<?php

namespace Warlikes\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Gecky\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MainController extends Controller
{		
	public function indexAction(Request $request)
	{
		$facebook = $this->container->get('facebook');		
		$loginUrl = $facebook->getConfiguredLoginUrl();
		
		return $this->render('Main/index.php', array(
			'loginUrl' => $loginUrl
		));
	}
	
	public function likeAction(Request $request)
	{
		return $this->render('Main/like.php');
	} 
	
	public function registerAction(Request $request)
	{
		/** REGISTER INTO DB **/
		$register = $request->get('register');
		if($register)
		{
			$facebook = $this->container->get('facebook');
			$fbId = $facebook->getUser();
			
			$name = $register['name'];
			$lastName = $register['last_name'];
			$dni = $register['dni'];
			$email = $register['email'];
			$birthDate = $register['birth_date'];
			$birthDate = $birthDate['year'].'-'.$birthDate['month'].'-'.$birthDate['day'];
			$hasCencosud = (isset($register['has_cencosud']) && $register['has_cencosud']!='no')?1:0;
			$newsletterEasy = (isset($register['newsletter_easy']) && $register['newsletter_easy']!='')?1:0;
			$newsletterCencosud = (isset($register['newsletter_cencosud']) && $register['newsletter_cencosud']!='')?1:0;
			$terms = $register['terms'];
			
			//Photo
			$registerFiles = $request->files->get('register');
			$photo = $registerFiles['photo'];
			
			if(!($photo instanceOf UploadedFile))
			{
				return $this->redirect($this->generateUrl('register'));
			}
			
			$photoFilename =  md5($photo->getClientOriginalName()).'.'.$photo->getClientOriginalExtension();
			$file = $photo->move('uploads', $photoFilename);
			
			//Guardado en base de datos
			$conn = $this->container->get('database')->getConnection();
			$result = $conn->insert('user', array(
				'fbid' => $fbId, 'name' => $name, 'last_name' => $lastName, 'dni' => $dni, 
				'email' => $email, 'birth_date' => $birthDate, 'photo' => $photoFilename, 'has_cencosud' => $hasCencosud, 
				'newsletter_easy' => $newsletterEasy, 'newsletter_cencosud' => $newsletterCencosud
			));
			
			$userId = $conn->lastInsertId();
			if($result == true && $userId)
			{
				return $this->redirect($this->generateUrl('user_photo', array('id' => $userId, 'new' => true)));
			}
		}
		
		return $this->render('Main/register.php', array(
		));
	}
		
	public function thanksAction(Request $request)
	{
		return $this->render('Main/thanks.php');
	}

	public function galleryAction(Request $request)
	{
		return $this->render('Main/gallery.php');
	}
	
	public function userPhotoAction(Request $request)
	{
		$userId = $request->get('id');
		
		$conn = $this->container->get('database')->getConnection();
		$user = $conn->fetchAssoc("SELECT * FROM user WHERE id = ?", array($userId));
				
		return $this->render('Main/userPhoto.php', array(
			'user' => $user
		));
	}
	
	public function userPhotoVoteAction(Request $request)
	{
		$userId = $request->get('id');
		$sessionVotedName = 'user_'.$userId.'_photo_voted';
		
		if(!$request->getSession()->get($sessionVotedName))
		{
			$conn = $this->container->get('database')->getConnection();
			$user = $conn->fetchAssoc("SELECT * FROM user WHERE id = ?", array($userId));
			if($user)
			{
				$conn->update('user', array('votes' => $user['votes']+1), array('id' => $userId));
				$request->getSession()->set($sessionVotedName, true);
			}
		}
		
		return $this->redirect($this->generateUrl('user_photo', array('id' => $userId)));
	}
	
	public function rankingAction(Request $request)
	{
		return $this->render('Main/ranking.php');
	}
		
	public function termsAction(Request $request)
	{
		return $this->render('Main/terms.php');
	}	
	
	public function facebookLoginAction(Request $request)
	{
		$facebook = $this->container->get('facebook');
		if(!$facebook->getUser())
		{
			$loginUrl = $facebook->getConfiguredLoginUrl();
			return new RedirectResponse($loginUrl);
		}
		
		return new RedirectResponse($this->container->get('routing.generator')->generate('homepage'));
	}
	
	public function startupAction(Request $request)
	{
		return $this->render('Main/startup.php');
	}
	
	public function registerListAction(Request $request)
	{
		//Get all data
		$conn = $this->container->get('database')->getConnection();
		$registers = $conn->fetchAll('SELECT * FROM user AS u ORDER BY u.is_winner DESC');
		
		return $this->render('Main/registerList.php', array(
			'registers' => $registers 
		), 'admin_layout');
	}
	
	public function deleteRegisterAction(Request $request)
	{
		//Get all data
		$conn = $this->container->get('database')->getConnection();
		$conn->delete('user', array('id' => $request->get('id')));
		
		return new RedirectResponse($this->container->get('routing.generator')->generate('register_list'));
	}
	
	public function generateWinnersAction(Request $request)
	{
		$conn = $this->container->get('database')->getConnection();
		
		//Unset old winners
		$conn->update('user', array('is_winner' => false), array(1 => 1));
		
		//Generate winners
		$possibleWinners = array();
		$registers = $conn->fetchAll('SELECT * FROM user AS u');
		foreach ($registers as $register)
		{
			$posibilities = ceil($register['monto'] / 200);
			for ($i=1; $i <= $posibilities; $i++)
			{
				$possibleWinners[] = $register['id'];
			}
		}
		
		shuffle($possibleWinners);
		$winners = array();
		foreach ($possibleWinners as $possibleWinner)
		{
			if(count($winners) >= 1)
				break;
			
			if(!in_array($possibleWinner, $winners))
			{
				$winners[] = $possibleWinner;
			}
		}
		
		foreach ($winners as $winner)
		{
			$conn->update('user', array('is_winner' => true), array('id' => $winner));
		}
		
		return new RedirectResponse($this->container->get('routing.generator')->generate('register_list'));
	}
	
	public function downloadExcelAction(Request $request)
	{
		//Guardado en base de datos
		$conn = $this->container->get('database')->getConnection();
		$registers = $conn->fetchAll('SELECT * FROM user');
	
		$view = $this->templating->render('Main/downloadExcel.php', array(
			'registers' => $registers
		));
	
		$response = new Response($view); 
		$response->headers->set('Content-Type', 'application/octet-stream');
		$response->headers->set('Content-Disposition', 'attachment; filename=registros.xls');
		$response->headers->set('Pragma', 'no-cache');
		$response->headers->set('Expires', '0');
		
		return $response;
	}
	
	protected function getViewsDir()
	{
		return __DIR__.'/../Resources/views/%name%';
	}
}