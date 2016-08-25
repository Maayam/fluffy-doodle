<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/map", name="mapPage")
     */
    public function mapAction(Request $request)
    {
        return $this->render('page/user-map.html.twig');
    }

    /**
     * @Route("/admin", name="adminPage")
     */
    public function admin_indexAction(Request $request)
    {
        return $this->render('page/admin-panel.html.twig');
    }
}
