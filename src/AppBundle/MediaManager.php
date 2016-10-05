<?php

namespace AppBundle;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Media;

class MediaManager extends Controller
{	

	public function __construct($container) {
		$this->container = $container;
	}
	
	
	public function get($service) {
		return $this->container->get($service);
	}
	
	public function addMedia($plot) {
		$media = new Media();
		
		$file = $plot->getFile();
		
		$filename = md5(uniqid()).'.'. $file->guessExtension();
		$media->setPlot($plot);
		$media->setPath($filename);
		
		$file = $file->move(
			$this->container->getParameter('picture_dir'),
			$filename
		);
		
		$this->generateThumbnail($file, $filename);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($media);
		$em->flush();
		
		$plot->setFile(null);
		
		return $media;
	}
	
	private function generateThumbnail($file, $filename) {
		$height = $this->getParameter('thumbs_height');
		$width = $this->getParameter('thumbs_width');
		
		$image = new \Imagick;
		
		$image->setBackgroundColor(new \ImagickPixel('transparent'));
		$image->pingImage($file);
		$image->readImage($file);
		
		$image->thumbnailImage($width, $height, true, true);
		
		//Use png extension for transparency in the thumbnail
		$image->writeImage($this->container->getParameter('thumbs_dir').'/'.$filename.".png");
		
		$image->destroy();	
	}
}
