<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlotController extends Controller
{
    /**
     * @Route("/plots", name="indexPlotsPage")
     */
    public function indexAction(Request $request){
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
	public function showAction($plotId)
	{
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
     * @Route("/addTestPlot", name="testPlotPage")
     */
    public function testCreateAction(){
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

	/**
	 *@Route("/findInView", name="findInViewPage")
	 */
	public function ajax_findInViewAction(Request $request){
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

}
