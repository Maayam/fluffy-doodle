<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * A class managing users
 */
class UserController extends Controller
{
	/**
	 * Create a new user from a submitted form
	 *
	 * @param $request The HTML request
	 * @return JSON containing the operation result
	 *
	 * @Route("/signup", name="signup")
	 */
    public function postUser(Request $request){
    	$user = new User();
        $form = $this->createForm(UserType::class, $user);

        //handling the submit request
	    $form->handleRequest($request);
	    if ($form->isSubmitted() && $form->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$page` variable has also been updated

	        $postUser = $form->getData();

		    $em = $this->getDoctrine()->getManager();

		    $em->persist($postUser);
		    $em->flush();

		    $this->addFlash(
            'notice',
            "l'utilisateur a bien été créé."
        	);

	        return $this->render('user/signupForm.html.twig', array(
	            'form' => $form->createView(),
			));
	    } //end if fom submited
	    else{
	        return $this->render('user/signupForm.html.twig', array(
	            'form' => $form->createView(),
			));
	    }
    }

	// /**
	//  * Create a form to create an user
	//  *
	//  * @param $request The HTML request
	//  * @return The HTML form to create an user 
	//  *
	//  * @Route("/signup", name="signupForm")
	//  * @Method({"GET"})
	//  */
	// public function getUserForm(Request $request){
 //        // create a plot and give it the coords where the user clicked
 //        $user = new User();
 //        $user->setName('');
 //        $user->setPassword('');
 //        $user->setEmail('');
 //        //build the form
 //        $form = $this->createForm(UserType::class, $user);

 //        return $this->render('page/signupForm.html.twig', array(
 //            'form' => $form->createView(),
	// 	));
	// }
}
