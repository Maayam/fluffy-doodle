<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageController extends Controller
{
    /**
     * @Route("/home", name="landingPage")
     */
    public function landAction(Request $request)
    {
        return $this->render('page/landingPage.html.twig');
    }

    /**
     * @Route("/map", name="map")
     */
    public function mapAction(Request $request)
    {
        return $this->render('page/user-map/index.html.twig', array("page" => "map"));
    }

    /**
     * @Route("/admin", name="adminPage")
     */
    public function admin_indexAction(Request $request)
    {
        return $this->render('page/admin-panel.html.twig');
    }
}
