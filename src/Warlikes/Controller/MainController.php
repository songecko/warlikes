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
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\DoctrineDbalSingleTableAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class MainController extends Controller
{		
	public function indexAction(Request $request)
	{
		$facebook = $this->container->get('facebook');
		$database = $this->container->get('database');
		
		$loginUrl = $facebook->getConfiguredLoginUrl();
		$target = "_top";
		
		//If logged in on facebook
    		if($fbId = $facebook->getUser())
    		{
			$conn = $database->getConnection();
    			$user = $conn->fetchArray('SELECT * FROM user WHERE fbid = ?', array($fbId));
			
			//If user is registered
    			if(!$user)
    			{	
				$loginUrl = $this->generateUrl('register');
				$target = "_self";
			}else
			{
				$loginUrl = null;
			}
		}
		
		return $this->render('Main/index.php', array(
			'loginUrl' => $loginUrl,
			'target' => $target
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
		$conn = $this->container->get('database')->getConnection();
		
		$queryBuilder = new QueryBuilder($conn);
		$queryBuilder->select('u.*')->from('user', 'u')->orderBy('id', 'DESC');
		
		return $this->renderUserPhotos($request, $queryBuilder, 'Main/gallery.php');
	}

	public function rankingAction(Request $request)
	{
		$conn = $this->container->get('database')->getConnection();
		
		$queryBuilder = new QueryBuilder($conn);
		$queryBuilder->select('u.*')->from('user', 'u')->orderBy('u.votes', 'ASC');
		
		return $this->renderUserPhotos($request, $queryBuilder, 'Main/ranking.php');
	}
	
	private function renderUserPhotos(Request $request, QueryBuilder $queryBuilder, $template)
	{		
		$countField = 'u.id';		
		$adapter = new DoctrineDbalSingleTableAdapter($queryBuilder, $countField);
		
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage(6);
		$pager->setCurrentPage($request->get('page', 1));
		
		$pagerView = new DefaultView();
		$galleryRoute = $this->generateUrl('gallery');
		$html = $pagerView->render($pager, function($page)
		{
			return '?page='.$page;
		}, array(
				'proximity' => 3,
				'container_template' => '<nav class="pagination">%pages%</nav>',
				'previous_message' => '&lt;',
				'next_message' => '&gt;',
		));
		
		return $this->render($template, array(
				'pager' => $pager,
				'pagination' => $html
		));
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
		$userPhotoId = $request->get('id');
		//$sessionVotedName = 'user_'.$userId.'_photo_voted';
		
		/*if(!$request->getSession()->get($sessionVotedName))
		{*/
			$facebook = $this->container->get('facebook');
			$fbId = $facebook->getUser();
			
			$conn = $this->container->get('database')->getConnection();
			
			$user = $conn->fetchAssoc("SELECT * FROM user WHERE fbid = ?", array($fbId));
			$userPhoto = $conn->fetchAssoc("SELECT * FROM user WHERE id = ?", array($userPhotoId));
			
			//Check if the user not voted to this photo
			if($user && $userPhoto)
			{
				$vote = $conn->fetchAssoc("SELECT * FROM user_vote WHERE user_id = ? AND voted_user_id = ?", array($user['id'], $userPhotoId));
				
				if(!$vote)
				{
					$conn->update('user', array('votes' => $userPhoto['votes']+1), array('id' => $userPhotoId));
					$conn->insert('user_vote', array(
						'user_id' => $user['id'], 
						'voted_user_id' => $userPhotoId,
						'ip' => $this->get_client_ip()
					));
				}
				//$request->getSession()->set($sessionVotedName, true);
			}
		//}
		
		return $this->redirect($this->generateUrl('user_photo', array('id' => $userPhotoId)));
	}
	
	private function get_client_ip() {
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
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
		/*$conn = $this->container->get('database')->getConnection();
		$registers = $conn->fetchAll('SELECT * FROM user AS u ORDER BY u.is_winner DESC');
		
		return $this->render('Main/registerList.php', array(
			'registers' => $registers 
		), 'admin_layout');*/
		$user = '1409787838';
		$facebook = $this->container->get('facebook');
		$notification_message = 'Brian has accepted your invite! Click here to play';
		$notification_app_link = 'http://facebook.com/yourapp';
		$accessToken = $facebook->getAppId().'|'.$facebook->getApiSecret();
		$notificationArray = array('access_token' => $accessToken, 'href' => $notification_app_link, 'template' => $notification_message);
		$fb_response = $facebook->api('/' . $user . '/notifications', 'POST', $notificationArray);
		var_dump($fb_response);die;
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