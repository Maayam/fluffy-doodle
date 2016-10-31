<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
	/**
	* @Route("/login", name="login")
	*/
	public function loginAction(Request $request){
	    $authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

	    return $this->render('security/login.html.twig', array(
	        'last_username' => $lastUsername,
	        'error'         => $error,
	    ));
	}

	// /**
	// * @Route("/login_check", name="loginCheck")
	// */
	// public function loginCheckAction(){
		
	// }

	/**
	* @Route("/logout", name="logout")
	*/
	public function logoutAction(){
		//logout

	}
}