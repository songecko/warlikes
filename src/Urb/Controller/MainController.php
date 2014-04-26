<?php

namespace Urb\Controller;

require_once __DIR__.'/../ColorsOfImage/colorsofimage.class.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Gecky\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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
			$newsletter = (isset($register['newsletter']) && $register['newsletter']!='')?1:0;
			$terms = $register['terms'];
			
			//Guardado en base de datos
			$conn = $this->container->get('database')->getConnection();
			$result = $conn->insert('user', array(
				'fbid' => $fbId, 'name' => $name, 'last_name' => $lastName, 'dni' => $dni, 
				'email' => $email, 'birth_date' => $birthDate, 'newsletter' => $newsletter
			));
			
			if($result == true)
			{
				return $this->redirect($this->generateUrl('startup'));
			}
		}
		
		return $this->render('Main/register.php', array(
		));
	}
	
	public function addLookAction(Request $request)
	{
		$facebook = $this->container->get('facebook');
		$database = $this->container->get('database');
		$conn = $database->getConnection();
		
		$photoId = $request->get('photoId');
		$photoSource = $request->get('photoSource');
		
		$photoContent = file_get_contents($photoSource);
		$filename = $photoId.'.jpg';
		$save = file_put_contents('uploads/'.$filename, $photoContent);
		
		//Get The user
		$fbId = $facebook->getUser();
		$user = $conn->fetchAssoc('SELECT * FROM user WHERE fbid = ?', array($fbId));
		
		$lookUrl = $lookUrl = $this->generateUrl('startup');
		if($user && $save)
		{
			$result = $conn->insert('user_look', array(
					'user_id' => $user['id'], 'image' => $filename
			));
			
			if($result)
				$lookUrl = $this->generateUrl('look_show', array('id' => $conn->lastInsertId()));
		}
		
		return new JsonResponse(array('lookUrl' => $lookUrl));
	}
	
	public function lookShowAction(Request $request)
	{
		$database = $this->container->get('database');
		$conn = $database->getConnection();
		
		$lookId = $request->get('id');
		
		$look = $conn->fetchAssoc('SELECT * FROM user_look WHERE id = ?', array($lookId));
		
		if(!$look)
		{
			$this->redirect($this->generateUrl('homepage'));
		}
		
		$lookColors = array(
			'#53716a' => array(
					'name' => 'AZUL',
					'image' => 'azul.jpg',
					'description' => 'El color Azul es la estrella de la nueva temporada. Es un color que desborda estilo y convierte un look de básicos en algo único, interesante. Utilizá el azul para poner ese toque de vida a los días grises que se acercan.',
					'tip' => 'Combinalo con  toques dorados o negros. Es el nuevo negro, comodín para todo outfit.'
			),
			'#5f0717' => array(
					'name' => 'BORDEAUX',
					'image' => 'bordeaux.jpg',
					'description' => 'Un rojo exótico y glamoroso que te invita a la aventura y destinos lejanos, reforzado por un rojo más sofisticado que añade brío, chispa y mucha actitud.<br/><br/>El Bourdeaux se traduce en elegancia y seguridad. Este color se adapta a sus mil y un versiones. En su gama más clásica, es ideal para el día, mientras que para la noche animate a un total look para impactar y robarte todas las miradas.',
					'tip' => 'Su versión más rockera, combinalo con calzas de punto, o es perfecto con remeras casuales.'
			),
			'#979fb1' => array(
					'name' => 'GRIS',
					'image' => 'gris.jpg',
					'description' => 'Perfecto para la oficina. Formal y sofisticado, el gris es uno de los colores más permeables de la familia invernal. Para un look más casual, usalo en sweaters, guantes, bufandas y sombreros.',
					'tip' => 'Para cortar con la seriedad del color, acompañalo de colores vibrantes como el rojo<br/> o el fucsia.'
			),
			'#000000' => array(
					'name' => 'NEGRO',
					'image' => 'negro.jpg',
					'description' => 'Clásico, inmortal, elegante. El color que nunca falla, combina con todo y se adapta a cualquier look. Combinalo con colores brillantes y vivos para hacer un juego de opuestos que renueven tu look sin esfuerzo.',
					'tip' => 'Las calzas negras símil cuero y el total black en tres piezas son geniales para usar en la oficina, y seguir hasta el after office. Vos, impecable.'
			),
			'#49696f' => array(
					'name' => 'VERDE',
					'image' => 'verde.jpg',
					'description' => 'Vestite del color "esperanza" durante los largos día del invierno. El verde llega pisando fuerte en sus diferentes tonalidades: esmeralda, pimienta, botella o caqui, todos son protagonistas.',
					'tip' => 'La moda militar lo ha convertido en el rey del street style.'
			)
		);
		
		$userLookColor = $look['color']?$look['color']:null;
		if(!$userLookColor)
		{
			$image = new \ColorsOfImage('uploads/'.$look['image'], 10, 1);
			$prominentColor = $image->getProminentColors();
			$prominentColor = $prominentColor[0];
	
			
			foreach ($lookColors as $lookColor => $lookColorData)
			{
				if (!$userLookColor)
				{
					$userLookColor = $lookColor;
				}else
				{
					if(\ColorsOfImage::getDistanceBetweenColors(\ColorsOfImage::HexToRGB($lookColor), \ColorsOfImage::HexToRGB($prominentColor)) 
						< \ColorsOfImage::getDistanceBetweenColors(\ColorsOfImage::HexToRGB($userLookColor), \ColorsOfImage::HexToRGB($prominentColor)))
					{
						$userLookColor = $lookColor;
					}	
				}
			}
			
			$conn->update('user_look', array('color' => $userLookColor), array('id' => $lookId));
		}
		
		return $this->render('Main/lookShow.php', array(
			'userLookColor'    => $userLookColor,
			'userLook' => $lookColors[$userLookColor]
		));
	}
	
	public function thanksAction(Request $request)
	{
		return $this->render('Main/thanks.php');
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