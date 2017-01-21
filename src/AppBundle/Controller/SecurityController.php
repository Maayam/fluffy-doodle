<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
	/**
	 */
	public function loginAction(Request $request){
	    $authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

			$session = new Session();
			$session->start();
			$session->set('username', $lastUsername);
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
	 */
	public function logoutAction(){
		//logout
		$session = new Session();
		$session->start();
		$session->clear();
	}
}
