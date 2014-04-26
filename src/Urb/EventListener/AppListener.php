<?php

namespace Urb\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AppListener implements EventSubscriberInterface
{
    private $container;

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
    	if (!$mobileDetect->isMobile() && $signedRequest == null)
    	{
    		//$event->setResponse($this->redirect($facebook->getTabUrl()));
    	}
    	
    	//If not page liked
    	if($signedRequest && !$signedRequest['page']['liked'] && !$this->isOnRoute($request, 'like'))
    	{
    		$this->redirectToRoute($event, 'like');
    		return;
    	}
    	
    	//If logged in on facebook
    	if($fbId = $facebook->getUser())
    	{
    		$conn = $database->getConnection();
    		$user = $conn->fetchArray('SELECT * FROM user WHERE fbid = ?', array($fbId));
    		
    		//If user is registered
    		if(!$user)
    		{
    			$this->redirectToRoute($event, 'register');
    			return;
    		}else 
    		{
    			if($this->isOnRoute('homepage') || $this->isOnRoute('register'))
    			{
    				$this->redirectToRoute($event, 'startup');
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
    
    protected function isOnRoute($routeName)
    {
    	$parameters = $this->container->get('matcher')->match($this->request->getPathInfo());
    	
    	return $parameters['_route'] == $routeName;
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
