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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserController extends Controller
{
	/**
	 *@Route("/user/add", name="addUser")
	 *@Method({"POST"})
	 */
    public function postUser(Request $request){
        // create a user
        $user = new User();

        //build the form
        $signupForm = $this->createForm(UserType::class, $user);

        //handling the submit request
	    $signupForm->handleRequest($request);

	    if ($signupForm->isSubmitted() && $signupForm->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$plot` variable has also been updated
	        $user = $signupForm->getData();
	        // ... perform some action, such as saving the plot to the database
	        // for example, if Plot is a Doctrine entity, save it!
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($user);
	        $em->flush(); //just like in debug_testCreateAction
	        return new JsonResponse(array('success' => true));
	    }
	    else{
	    	return new JsonResponse(array('success' => false));
	    }
    }

	/**
	 *@Route("/signup", name="signupForm")
	 *@Method({"GET"})
	 */
	public function getUserForm(Request $request){
        // create a plot and give it the coords where the user clicked
        $user = new User();
        $user->setName('');
        $user->setPassword('');
        $user->setMail('');
        //build the form
        $form = $this->createForm(UserType::class, $user);

        return $this->render('page/signupForm.html.twig', array(
            'form' => $form->createView(),
		));
	}
}
