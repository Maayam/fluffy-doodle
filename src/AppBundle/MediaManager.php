<?php

namespace AppBundle;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Media;

/**
 * A service to manage media and especially images
 */
class MediaManager extends Controller
{	

	public function __construct($container) {
		$this->container = $container;
	}
	
	
	public function get($service) {
		return $this->container->get($service);
	}

	/**
	 * Add a media to a plot
	 *
	 * @param $plot The plot which will be associated with the media
	 * @return The created media
	 */	
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
	
	/**
	 * Generate a thumbnail from an uploaded file
	 *
	 * @param $file The uploaded file
	 * @param $filename The filename associated with the file
	 */
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
