<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     *@Route("/testAjax", name="testAjax")
     */
    public function testAjaxAction(){
    	return new JsonResponse(array(
    		'data' => "this is JSON Data.",
    		'Warning' => "DONT MESS WITH THIS SHIT OK !?"
    	));
    }
}
