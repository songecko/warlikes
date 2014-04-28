<?php

namespace Warlikes\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Gecky\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Filter\Transformation;
use Symfony\Component\Filesystem\Filesystem;

class ImagineController extends Controller
{	
	private $filesystem;
	
	public function __construct()
	{
		parent::__construct();

		$this->filesystem = new Filesystem();
	}
	
	public function resizeAction(Request $request)
	{
		//Parameters
		$imageSrc = new File('uploads/'.$request->get('src'));
		$width = $request->get('width');
		$height = $request->get('height');
		
		
		//Check if is cached
		try 
		{
			$imagine = new Imagine();
			$cacheImagePath = $this->getCacheImagePath($imageSrc, $width, $height);
			if($this->filesystem->exists($cacheImagePath))
			{
				$image = $imagine->open($cacheImagePath);
			}else 
			{
				//Resize
				$transformation = new Transformation();				
				$transformation->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
				$image = $transformation
					->apply($imagine->open($imageSrc->getPathname()))
					->save($cacheImagePath);
				
			}			
		} catch (Imagine\Exception\Exception $e) {}
		
		//Create the response
		$response = new Response($image->get($imageSrc->getExtension()), 200);
    	$response->headers->set('Content-Type', 'image/jpg');
		
		return $response;
	}

	protected function getCacheImagePath(File $imageSrc, $width, $height)
	{
		$baseCacheDir = 'cache/media/image/';
		$cacheDir = $baseCacheDir.$width.'/'.$height.'/';

		if(!$this->filesystem->exists($cacheDir))
		{
			$this->filesystem->mkdir($cacheDir, 0777);	
		}
		
		return $cacheDir.$imageSrc->getFilename();
	}
}