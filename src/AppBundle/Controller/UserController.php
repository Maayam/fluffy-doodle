<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
	 * Create a new user from a register form
	 *
	 * @param $request The HTML request
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

			$plainPassword = $postUser->getPassword();
			$encoder = $this->container->get('security.password_encoder');
			$hashed = $encoder->encodePassword($postUser, $plainPassword);

			$postUser->setPassword($hashed);

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

	/**
	 * Shows a user profile
	 *
	 * @param $request The HTML request
	 *
	 * @Route("/user/{username}", name="viewUser")
	 */
    public function showUser(Request $request, $username){
		
		$user = $this->findUserByName($username);
		
		return $this->render('page/userProfile.html.twig', array("user"=>$user));
    }

	private function findUserByName($name) {
	//returns all plots which match with a name
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
			'SELECT user
			FROM AppBundle\Entity\User user
			WHERE user.username = :name'
		)->setParameters(array('name' => $name));;

		$content = $query->getResult();

		if(count($content) == 0){
			$content = null;
		}
		else{
			$content = $content[0];
		}

		return $content;
	}
}
