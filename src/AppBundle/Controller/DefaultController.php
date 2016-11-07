<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="defaultPage", defaults={"_locale"="en"})
     * @Route("/{_locale}/", name="defaultPageLoc", requirements={"_locale"="en|fr"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/default.html.twig');
    }
}
