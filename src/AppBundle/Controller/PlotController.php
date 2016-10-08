<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use AppBundle\Form\PlotType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * A class managing plots
 */
class PlotController extends Controller
{


	/**
	 * Render a plot page
	 *
	 * @param $request The HTML request
	 * @param $id The id of the plot to render
	 * @return The rendered page
	 *
	 * @Route("/view/plot/{id}", name="showPlot")
	 * @Method({"GET"})
	 */
	public function viewPlot(Request $request, $id) {
	
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT plot
			FROM AppBundle\Entity\Plot plot
			WHERE plot.id = :id'
		)->setParameters(array('id'=>$id));
		
		$plot = $query->getResult()[0];
		
		$pictures = [];
		
		foreach($plot->getPictures() as $media) {
			$pictures[] = $media->getPath();
		}
		
		return $this->render('page/plotView.html.twig', array(
			"id" => $plot->getId(),
			"pictures" => $pictures,
			"note" => $plot->getNote(),
			"name" => $plot->getName(),
			"lat" => $plot->getLat(),
			"lng" => $plot->getLng()
		));
	} 

	/**
	 * Create a plot from a submitted form
	 *
	 * Meant to respond to an AJAX request
	 *
	 * @param $request The HTML request
	 * @return JSON containing the created plot informations
	 *
	 * @Route("/plot", name="postPlot")
	 * @Method({"POST"})
	 */
    public function postPlot(Request $request){
        // create a plot
        $plot = new Plot();
        
        if(!$request->isXmlHttpRequest())
        	return;

        //build the form
        $form = $this->createFormBuilder($plot)
	        ->add('Lat', HiddenType::class )
	        ->add('Lng', HiddenType::class )
	        ->add('Name', TextType::class )
	        ->add('Note', TextareaType::class )
	        ->add('File', FileType::class, array('label'=>'Picture', 'required'=>false))
            ->add('save', SubmitType::class, array('label' => 'Create Plot'))
            ->getForm();

        //handling the submit request
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$plot` variable has also been updated
	        $plot = $form->getData();
	        
	        //If a picture was uploaded
	        if($plot->getFile() != null) {
	        	$mediaManager = $this->get("media_manager");
	        	
	        	$plot->addPicture($mediaManager->addMedia($plot));
	        }
	        // ... perform some action, such as saving the plot to the database
	        // for example, if Plot is a Doctrine entity, save it!
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($plot);
	        $em->flush(); //just like in debug_testCreateAction
	        
	        $pictures = null;
	        
	        if(isset($plot->getPictures()[0]))
	        	$pictures = $plot->getPictures()[0]->getPath();
	        
	        $html = $this->renderView("page/user-map/plotPopup.html.twig", array(
				"description" => $plot->getNote(),
				"picture" => $pictures,
				"name" => $plot->getName(),
				"id" => $plot->getId()
			));
	        
	        return new JsonResponse(array('success' => true, 'html'=>$html));
	    }
	    else{
	    	return new JsonResponse(array('success' => false));
	    }
    }

	/**
	 * Create a form to create a plot
	 *
	 * Meant to respond to an AJAX request
	 *
	 * @param $request The HTML request
	 * @return The HTML form to create a plot
	 * 
	 * @Route("/plot/form", name="getPlotForm")
	 * @Method({"GET"})
	 */
	public function getPlotForm(Request $request){

		if(!$request->isXmlHttpRequest())
			return;

		$lat = $request->query->get('lat');
		$lng = $request->query->get('lng');


        // create a plot and give it the coords where the user clicked
        $plot = new Plot();
        $plot->setLat($lat);
        $plot->setLng($lng);
        $plot->setName('');
        $plot->setNote('');

		//fetch tag_list
		 $em = $this->getDoctrine()->getManager();
		 $query = $em->createQuery(
		 	"SELECT tag.id, tag.name ".
		 	"FROM AppBundle\Entity\Tag tag ");
		 $tag_list = $query->getResult();

        //build the form
        $plotForm = $this->createFormBuilder($plot)
	        ->add('Lat', HiddenType::class )
	        ->add('Lng', HiddenType::class )
	        ->add('Name', TextType::class )
	        ->add('Note', TextareaType::class )
	        ->add('File', FileType::class, array('label'=>'Picture', 'required'=>false))
            ->add('save', SubmitType::class, array('label' => 'Create Plot'))
            ->getForm();

        return $this->render('page/plotForm.html.twig', array(
            'form' => $plotForm->createView(),
            'tags' => $tag_list
		));
	}

	/**
	 * Router for finding functions
	 *
	 * @param $request The HTML request
	 * @return JSON representation of the plots matching the research
	 *
	 * @Route("/plot/search", name="findPlot")
	 * @Method({"GET"})
	 */
	public function findPlots(Request $request){
		$filter = $request->query->get('filter');
		
		$plots = null;

		if($filter == 'plotsInBox'){
			$box = [
				"minLat" => $request->query->get('minLat'),
				"minLng" => $request->query->get('minLng'),
				"maxLat" => $request->query->get('maxLat'),
				"maxLng" => $request->query->get('maxLng')
			];
			$plots = $this->findInBox($box);
		}

		if($filter == 'findByName'){
			$name = $request->query->get('search');
			$plots = $this->findByName($name);
		}
		
		return new JsonResponse($this->createPlotHtml($plots));
	}

	/**
	 * Find all plots contained in a certain space
	 *
	 * The space is formed by two points [minLat, minLng] and 
	 * [maxLat, maxLng]
	 *
	 * @param $box The containing space
	 * @return All plots contained in the box
	 */
	private function findInBox($box){

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT plot.lat, plot.lng, plot.name, plot.note, media.path, plot.id
		    FROM AppBundle\Entity\Plot plot LEFT JOIN AppBundle\Entity\Media media
		    WITH plot.id = media.plot
		    WHERE plot.lat > :minLat
		    AND plot.lat < :maxLat
		    AND plot.lng > :minLng
		    AND plot.lng < :maxLng'
		)->setParameters(array(
			'minLat' => $box['minLat'],
			'minLng' => $box['minLng'],
			'maxLat' => $box['maxLat'],
			'maxLng' => $box['maxLng']
		));

		$plots = $query->getResult();
		return $plots;
	}

	/**
	 * Find all plots matching with a name
	 *
	 * @param $name The name to match with
	 * @return All matching plots
	 */
	private function findByName($name) {
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
			'SELECT plot.lat, plot.lng, plot.name, plot.note, media.path, plot.id
			FROM AppBundle\Entity\Plot plot LEFT JOIN AppBundle\Entity\Media media
			WITH plot.id = media.plot
			WHERE plot.name LIKE :name'
		)->setParameters(array('name' => "%". $name."%"));

		$plots = $query->getResult();
		return $plots;
	}
	
	/**
	 * Create the HTML for the popup associated with the plot on the map
	 *
	 * @param $plots The plots meant to be drawn on the map
	 * @return All plots with the generated popup HTML
	 */
	private function createPlotHtml($plots) {

		if($plots == null)
			return null;

		foreach($plots as $index => $plot) {
		
			$plots[$index]["html"] = $this->renderView("page/user-map/plotPopup.html.twig", array(
				"description" => $plot["note"],
				"picture" => $plot["path"],
				"name" => $plot["name"],
				"id" => $plot["id"]
			)); 
		}
		
		return $plots;
	}
} 
