<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use AppBundle\Entity\User;
use AppBundle\Entity\Performance;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A class managing plots
 */
class PerformanceController extends Controller
{
	/**
	 * Mark a plot as danced
	 *
	 * @param $request The HTML request
	 * @return JSON
	 *
	 * @Route("/performance", name="addPerformance")
	 * @Method({"POST"})
	 */
	public function addPerformance(Request $request){
		//getForm
		$username = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
		$params = $request->request->all();
		$plotId = $params['plotId'];
		$formData = $params['form'];
		// //getPlot
    $plot = $this->getDoctrine()
        ->getRepository('AppBundle:Plot')
        ->find($plotId);
		// //getUser
		$user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(
			array('username' => $username)
		);

		$perf = new Performance();

		//build the form
		$form = $this->createFormBuilder($perf)
			->add('youtube', TextType::class )
			->add('niconicoDouga', TextType::class )
			->add('biribiri', TextType::class )
			->getForm();

		//handling the submit request
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$perf = $form->getData();
			$perf->setPlot($plot);
			$perf->setPerformer($user);
			$em->persist($perf);
			$em->flush(); //just like in debug_testCreateAction
			return new JsonResponse(true);
		}
		else{
			return new JsonResponse(false);
		}
	}
} 
