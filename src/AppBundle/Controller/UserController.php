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
	 * Shows a user profile
	 *
	 * @param $request The HTML request
	 * @param $id The id of the user
	 * @return The rendered page
	 *
	 * @Route("/profile/{id}", requirements={"id" = "\d+"}, name="viewUser")
	 * @Method({"GET"})
	 */
	public function showUser(Request $request, $id){
		
		$user = $this->getUserById($id);
		
		if($user) {
			return $this->render('FOSUserBundle:Profile:show.html.twig', array("user"=>$user));
		} else {
			throw $this->createNotFoundException('User not found');
		}
	}
	
	/**
	 * Check wether an user is logged
	 *
	 * @Route("/user/isLoggedin", name="isLoggedin")
	 * @Method({"GET"})
	 */
	public function isLogged(Request $request) {
	
		// if($request->isXmlHttpRequest()) {
			$securityContext = $this->container->get('security.authorization_checker');
			$isLogged = $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
		
			return new JsonResponse(array("isLoggedIn"=>$isLogged));
			
		// } else {
			// throw $this->createNotFoundException('Page not found');
		// }
	}

	/**
	 * Get a user by its id
	 *
	 * @param $id The id to match with
	 * @return The matching user
	 */
	private function getUserById($id) {
		$em = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\User');

		$user = $em->findOneById($id);	
		
		return $user;
	}

	/**
	 * Router for finding functions
	 *
	 * @param $request The HTML request
	 * @return User List matching the research
	 *
	 * @Route("/user/search", name="findUsers", defaults={"_locale"="en"})
	 * @Route("/{_locale}/user/search", name="findUsersLoc", requirements={"_locale"="en|fr"})
	 * @Method({"GET"})
	 */
	public function findUsers(Request $request){
		
		$users = null;
		$name = $request->query->get('search');
		
		if($name != null) {
			$box['search'] = '%'.$name.'%';
			$box['filter'] = $request->query->get('filter');
		}
		
		$users = $this->findLikeName($box['search']);
		
		return new JsonResponse($this->createPlotHtml($plots));
	}

	// /**
	//  * Find all users matching the keyword
	//  *
	//  * @param $search the keyword
	//  * @return All users matching the search term.
	//  */
	// private function findLikeName($search){
		
	// 	$em = $this->getDoctrine()
	// 	->getManager()
	// 	->getRepository('AppBundle\Entity\Plot')
	// 	->createQueryBuilder('u');
		
		// $query = $em->select('u')
		// ->andWhere('u.name LIKE :search')
		// ->setParameters($search);
		
	// 	$plots = $query->getQuery()->getResult();
		
	// 	return $plots;
	// }
}
