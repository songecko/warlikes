<?php

namespace Warlikes\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AppListener implements EventSubscriberInterface
{
    private $container;
    private $request;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
    	$this->request = $event->getRequest();
    	
    	$mobileDetect = $this->container->get('mobile_detect');
    	$facebook = $this->container->get('facebook');
    	$database = $this->container->get('database');
    	
    	$signedRequest = $facebook->getSignedRequest();
    	
    	//If is on desktop but not on facebook tab
    	if ($this->isOnRoute(array('homepage'))
    		&& !$mobileDetect->isMobile() 
			&& (!isset($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == null)
			|| $this->request->get('code')))
    	{
    		//$event->setResponse($this->redirect($facebook->getTabUrl()));
			//return;
    	}
    	
    	//If on liked
		if($this->isOnRoute('like'))
			return;
		
    	//If not page liked
    	if($signedRequest && !$signedRequest['page']['liked'])
    	{
    		$this->redirectToRoute($event, 'like');
    		return;
    	}

    	//Exception routes
    	if($this->isOnRoute('terms') || $this->isOnRoute('gallery') || $this->isOnRoute('ranking') || $this->isOnRoute('user_photo')  || $this->isOnRoute('imagine_resize'))
    		return;
		
    	//If logged in on facebook
    	if($fbId = $facebook->getUser())
    	{
    		$conn = $database->getConnection();
    		$user = $conn->fetchArray('SELECT * FROM user WHERE fbid = ?', array($fbId));
    		
    		//If user is registered
    		if(!$user)
    		{
			if($this->isOnRoute('homepage'))
				return; 
				
    			$this->redirectToRoute($event, 'register');
    			return;
    		}else 
    		{
    			if($this->isOnRoute('homepage') || $this->isOnRoute('register'))
    			{
    				$this->redirectToRoute($event, 'gallery');
    				return;
    			}
    		}
    	}else
    	{
    		$this->redirectToRoute($event, 'homepage');
    		return;
    	}
    }
    
    protected function redirectToRoute(GetResponseEvent $event, $routeName)
    {
    	if(!$this->isOnRoute($routeName))
    		$event->setResponse($this->redirect($this->generateUrl($routeName)));
    }
    
    protected function isOnRoute($routes)
    {
    	//TODO: Refactorizacion (crear un Matcher)
    	if(!is_array($routes))
    		$routes = array($routes);

    	$onRoute = false;
    	foreach ($routes as $routeName)
    	{
    		$parameters = $this->container->get('matcher')->match($this->request->getPathInfo());
    		$onRoute = ($parameters['_route'] == $routeName)?true:$onRoute;
    	}
    	
    	return $onRoute;
    }
    
    protected function redirect($url)
    {
    	return new RedirectResponse($url);
    }
    
    protected function generateUrl($routeName)
    {
    	return $this->container->get('routing.generator')->generate($routeName);
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 32)),
        );
    }
}
