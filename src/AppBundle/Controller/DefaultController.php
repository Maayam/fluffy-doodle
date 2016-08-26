<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="defaultPage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/default.html.twig');
    }

    /**
     * @Route("/addTestPlot", name="testPlot")
     */
    public function createAction(){

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

	    return $this->render('default/plot.html.twig', ['plot' => $plot]);
	}
}
