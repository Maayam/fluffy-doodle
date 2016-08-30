<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlotController extends Controller
{
    /**
     * @Route("/plots", name="indexPlotsPage")
     */
    public function debug_indexAction(Request $request){
    	//index all plots
	    $plots = $this->getDoctrine()
	        ->getRepository('AppBundle:Plot')
	        ->findAll();

	    if (!$plots) {
	        throw $this->createNotFoundException(
	            'No product found for id '.$plots
	        );
	    }
        return $this->render('default/plots.html.twig', ['plots' => $plots]);
    }

    /**
     * @Route("/plot/{plotId}", name="viewPlotPage")
     */
	public function debug_showAction($plotId){
		//shows a single Plot By id in a rough template
	    $plot = $this->getDoctrine()
	        ->getRepository('AppBundle:Plot')
	        ->find($plotId);

	    if (!$plot) {
	        throw $this->createNotFoundException(
	            'No product found for id '.$plot
	        );
	    }

	    return $this->render('default/plot.html.twig', ['plot' => $plot]);
	}


	/**
	 * @Route("/findPlotByName", name="findPlotByName")
	 */
	public function ajax_findByName(Request $request) {
		//meant for AJAX : responds a JSON array of all plots which match with a name

		$name = $request->query->get('search');

		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
			"SELECT plot.lat, plot.lng, plot.name, plot.note ".
			"FROM AppBundle\Entity\Plot plot ".
			"WHERE plot.name LIKE :name "
		)->setParameters(array('name' => "%". $name."%"));

		$plots = $query->getResult();

		return new JsonResponse($plots);
	}

	/**
	 *@Route("/findInView", name="findInViewPage")
	 */
	public function ajax_findInViewAction(Request $request){
		//meant for AJAX: responds a JSON array of all plots contained in the box formed the two points [minLat, minLng] and [maxLat, maxLng]

		$bounds = [
			"minLat" => $request->query->get('minLat'),
			"minLng" => $request->query->get('minLng'),
			"maxLat" => $request->query->get('maxLat'),
			"maxLng" => $request->query->get('maxLng')
		];

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT plot.lat, plot.lng, plot.name, plot.note
		    FROM AppBundle\Entity\Plot plot
		    WHERE plot.lat > :minLat
		    AND plot.lat < :maxLat
		    AND plot.lng > :minLng
		    AND plot.lng < :maxLng'
		)->setParameters(array(
			'minLat' => $bounds['minLat'],
			'minLng' => $bounds['minLng'],
			'maxLat' => $bounds['maxLat'],
			'maxLng' => $bounds['maxLng']
		));

		$plots = $query->getResult();

		return new JsonResponse($plots);
	}

	/**
	 *@Route("/getPlotForm", name="getFormPage")
	 */
    public function ajax_getFormAction(Request $request){
    	//meant to respond to AJAX. returns the HTML form to create a plot as response

		$lat = $request->query->get('lat');
		$lng = $request->query->get('lng');


        // create a plot and give it the coords where the user clicked
        $plot = new Plot();
        $plot->setLat($lat);
        $plot->setLng($lng);
        $plot->setName('');
        $plot->setNote('');

        //build the form
        $form = $this->createFormBuilder($plot)
	        ->add('Lat', NumberType::class )
	        ->add('Lng', NumberType::class )
	        ->add('Name', TextType::class )
	        ->add('Note', TextareaType::class )
            ->add('save', SubmitType::class, array('label' => 'Create Plot'))
            ->getForm();

        //handling the submit request
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$plot` variable has also been updated
	        $plot = $form->getData();
	        // ... perform some action, such as saving the plot to the database
	        // for example, if Plot is a Doctrine entity, save it!
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($plot);
	        $em->flush(); //just like in debug_testCreateAction
	        return new JsonResponse(array('status' => 'success'));
	    }


        return $this->render('page/plotForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/addPlot", name="addPlot")
     */
    public function ajax_AddAction(Request $request){
    	//meant to accept only AJAX. adds a plot to the db

    	$data = $request->request->all();

	    $plot = new Plot();
	    $plot->setLat($data['Lat']);
	    $plot->setLng($data['Lng']);
	    $plot->setName($data['Name']);
	    $plot->setNote($data['Note']);

	    $em = $this->getDoctrine()->getManager();

	    // tells Doctrine you want to (eventually) save the Product (no queries yet)
	    $em->persist($plot);
	    // actually executes the queries (i.e. the INSERT query)
	    $em->flush();

	    return new JsonResponse(array('status' => 'success'));
	}

    /**
     * @Route("/addTestPlot", name="testPlotPage")
     */
    public function debug_testCreateAction(){
    	//dev function to add manually a plot to the db
    	//(change values below)
	    $plot = new Plot();
	    $plot->setLat(47.200549);
	    $plot->setLng(-1.544480);
	    $plot->setName('Random Plot...');
	    $plot->setNote("I'm random!");

	    $em = $this->getDoctrine()->getManager();

	    // tells Doctrine you want to (eventually) save the Product (no queries yet)
	    $em->persist($plot);

	    // actually executes the queries (i.e. the INSERT query)
	    $em->flush();

	    return $this->render('default/addPlot.html.twig', ['plot' => $plot]);
	}
}
