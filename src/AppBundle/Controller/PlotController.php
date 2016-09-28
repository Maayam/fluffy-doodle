<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PlotController extends Controller
{

	/**
	 *@Route("/plot", name="postPlot")
	 *@Method({"POST"})
	 */
    public function postPlot(Request $request){
    //meant to respond to AJAX. returns the HTML form to create a plot as response

		$lat = $request->request->get('lat');
		$lng = $request->request->get('lng');


        // create a plot and give it the coords where the user clicked
        $plot = new Plot();
        $plot->setLat($lat);
        $plot->setLng($lng);
        $plot->setName('');
        $plot->setNote('');

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
	        return new JsonResponse(array('success' => true));
	    }


        return $this->render('page/plotForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

	/**
	 *@Route("/plot", name="getPlot")
	 *@Method({"GET"})
	 */
	public function getPlot(Request $request){
		$filter = $request->query->get('filter');

		if($filter == 'plotsInBox'){
			$box = [
				"minLat" => $request->query->get('minLat'),
				"minLng" => $request->query->get('minLng'),
				"maxLat" => $request->query->get('maxLat'),
				"maxLng" => $request->query->get('maxLng')
			];
			$plots = $this->findInBox($box);
			return new JsonResponse($plots);
		}

		if($filter == 'findByName'){
			$name = $request->query->get('search');
			$plots = $this->findByName($name);
			return new JsonResponse($plots);
		}
	}

	private function findInBox($box){
	//returns all plots contained in the box formed by the two points [minLat, minLng] and [maxLat, maxLng]
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT plot.lat, plot.lng, plot.name, plot.note
		    FROM AppBundle\Entity\Plot plot
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

	private function findByName($name) {
	//returns all plots which match with a name
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
			"SELECT plot.lat, plot.lng, plot.name, plot.note ".
			"FROM AppBundle\Entity\Plot plot ".
			"WHERE plot.name LIKE :name "
		)->setParameters(array('name' => "%". $name."%"));

		$plots = $query->getResult();
		return $plots;
	}
} 
