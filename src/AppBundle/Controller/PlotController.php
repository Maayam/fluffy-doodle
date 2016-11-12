<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Plot;
use AppBundle\Entity\Tag;
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
class PlotController extends Controller
{


	/**
	 * Render a plot page
	 *
	 * @param $request The HTML request
	 * @param $id The id of the plot to render
	 * @return The rendered page
	 *
	 * @Route("/view/plot/{id}", name="viewPlot")
	 * @Method({"GET", "POST"})
	 */
	public function viewPlot(Request $request, $id) {
		$em = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\Plot');

		$plot = $em->findOneById($id);
	
		if($request->isMethod('POST')) {
			foreach($request->files as $file) {
				$plot->setFile($file);
				$mediaManager = $this->get("media_manager");
				$plot->addPicture($mediaManager->addMedia($plot));
			}
		}
		
		return $this->render('page/plotView.html.twig', array("plot"=>$plot));
	} 

	/**
	 * Create a plot from a submitted form
	 *
	 * Meant to respond to an AJAX request
	 *
	 * @param $request The HTML request
	 * @return JSON containing the created plot informations
	 *
	 * @Route("/plot", name="postPlot")
	 * @Method({"POST"})
	 */
	public function postPlot(Request $request){
		// create a plot
		$plot = new Plot();
		
		if(!$request->isXmlHttpRequest())
			return;

		//build the form
		$form = $this->createFormBuilder($plot)
			->add('Lat', HiddenType::class )
			->add('Lng', HiddenType::class )
			->add('Name', TextType::class )
			->add('Note', TextareaType::class )
			->add('File', FileType::class, array('label'=>'Picture', 'required'=>false))
			->add('Tags', TextType::class, array('required'=>false, 'mapped'=>false))
			->add('save', SubmitType::class, array('label' => 'Create Plot'))
			->getForm();

		//handling the submit request
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$plot = $form->getData();
			
			//If a picture was uploaded
			if($plot->getFile() != null) {
				$mediaManager = $this->get("media_manager");
				
				$plot->addPicture($mediaManager->addMedia($plot));
			}

			$stringTag = $form->get('Tags')->getData();

			$tagsName = array_map('trim', explode(',', $stringTag));

			$tags = new ArrayCollection();
			
			foreach($tagsName as $tagName) {
				$tmp = $em->getRepository('AppBundle\Entity\Tag')->findOneByName($tagName);
				
				if($tagName != "") {
					if($tmp == null) {
						$tag = new Tag();
						$tag->setName($tagName);
						$tags->add($tag);
					} else {
						$tags->add($tmp);
					}
				}
			}

			$plot->setTags($tags);

		   
			$em->persist($plot);
			$em->flush(); //just like in debug_testCreateAction
			
			$html = $this->renderView("page/user-map/plotPopup.html.twig", array("plot"=>$plot));
			
			return new JsonResponse(array('success' => true, 'html'=>$html));
		}
		else{
			return new JsonResponse(array('success' => false));
		}
	}

	/**
	 * @Route("/plot/form", name="getPlotForm")
	 * @Method({"GET"})
	 */
	public function getPlotForm(Request $request){

		if(!$request->isXmlHttpRequest())
			return;

		$lat = $request->query->get('lat');
		$lng = $request->query->get('lng');


		// create a plot and give it the coords where the user clicked
		$plot = new Plot();
		$plot->setLat($lat);
		$plot->setLng($lng);
		$plot->setName('');
		$plot->setNote('');

		//fetch tag_list
		 $em = $this->getDoctrine()->getManager();
		 $query = $em->createQuery(
		 	"SELECT tag.id, tag.name ".
		 	"FROM AppBundle\Entity\Tag tag ");
		 $tag_list = $query->getResult();

		//build the form
		$plotForm = $this->createFormBuilder($plot)
			->add('Lat', HiddenType::class )
			->add('Lng', HiddenType::class )
			->add('Name', TextType::class )
			->add('Note', TextareaType::class )
			->add('File', FileType::class, array('label'=>'Picture', 'required'=>false))
			->add('Tags', TextType::class, array(
				'required'=>false, 
				'mapped'=>false, 
				'attr'=>array('placeholder'=>'Enter commas separated tags')
			))
			->add('save', SubmitType::class, array('label' => 'Create Plot'))
			->getForm();

		return $this->render('page/plotForm.html.twig', array(
			'form' => $plotForm->createView(),
		));
	}


	/**
	 * Mark a plot as danced
	 *
	 * @param $request The HTML request
	 * @return JSON
	 *
	 * @Route("/plot/markAsDanced", name="markAsDanced")
	 * @Method({"POST", "GET"})
	 */
	public function markAsDancedAction(Request $request){

		$params = $request->request->all();

		$manager = $this->getDoctrine()->getManager();

		//get Plot (may not be necessary)
		$plotRepo = $manager->getRepository('AppBundle\Entity\Plot');
		$plot = $plotRepo->findOneById($params['plotId']);

		//get User
		$userRepo = $manager->getRepository('AppBundle\Entity\user')->createQueryBuilder('u');
		$user = $userRepo->select('u')
					->where('u.username = :name')
					->setParameter('name', $params['username'])
					->getQuery()
					->getResult()[0];

		$user_id = $user->getId();
		$plot_id = $plot->getId();

		$performance = new Performance();
		$performance->setPerformer($user);
		$performance->setPlot($plot);

		$manager->persist($performance);
		$manager->flush();

		return new JsonResponse(array('success' => true));
	}

	/**
	 * Router for finding functions
	 *
	 * @param $request The HTML request
	 * @return JSON representation of the plots matching the research
	 *
     * @Route("/plot/search", name="findPlot")
	 * @Method({"GET"})
	 */
	public function findPlots(Request $request){
		
		$plots = null;
		$name = $request->query->get('search');
		
		$box = ["minLat" => $request->query->get('minLat'),
				"minLng" => $request->query->get('minLng'),
				"maxLat" => $request->query->get('maxLat'),
				"maxLng" => $request->query->get('maxLng')
		];
		
		if($name != null) {
			$box['search'] = '%'.$name.'%';
			$box['filter'] = $request->query->get('filter');
		}
		
		$plots = $this->findInBox($box);
		
		return new JsonResponse($this->createPlotHtml($plots));
	}

	/**
	 * Find all plots contained in a certain space
	 *
	 * The space is formed by two points [minLat, minLng] and 
	 * [maxLat, maxLng]
	 *
	 * @param $box The containing space
	 * @return All plots contained in the box
	 */
	private function findInBox($box){
		
		$em = $this->getDoctrine()
		->getManager()
		->getRepository('AppBundle\Entity\Plot')
		->createQueryBuilder('p');
		
		$query = $em->select('p')
					->where('p.lat > :minLat')
					->andWhere('p.lat < :maxLat')
					->andWhere('p.lng > :minLng')
					->andWhere('p.lng < :maxLng');
					
		if(isset($box['search'])) {
			if($box['filter'] == 'name' || $box['filter'] == 'none') {
				$query->andWhere('p.name LIKE :search');
				unset($box['filter']);
				$query = $query->setParameters($box);
			} 
			else if($box['filter'] == 'tag'){
				$query->leftJoin('p.tags', 't')
					  ->andWhere('t.name LIKE :search');
				unset($box['filter']);
				$query = $query->setParameters($box);
			}
		} else {
			$query = $query->setParameters($box);
		}
		
		$plots = $query->getQuery()->getResult();
		
		return $plots;
	}

	/**
	 * Find all plots matching with a name
	 *
	 * @param $name The name to match with
	 * @return All matching plots
	 */
	private function findByName($name) {
		
		$em = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\Plot')->createQueryBuilder('p');
		
		$plots = $em->select('p')
					->where('p.name LIKE :name')
					->setParameter('name', $name)
					->getQuery()
					->getResult();
		
		
		return $plots;
	}
	
	/**
	 * Create the HTML for the popup associated with the plot on the map
	 *
	 * @param $plots The plots meant to be drawn on the map
	 * @return All plots with the generated popup HTML
	 */
	private function createPlotHtml($plots) {

		if($plots == null)
			return null;
		$html = [];
		foreach($plots as $index => $plot) {
			$html[$index] = [];
			$html[$index]['lat'] = $plot->getLat();
			$html[$index]['lng'] = $plot->getLng();
			$html[$index]["html"] = $this->renderView("page/user-map/plotPopup.html.twig", array("plot"=>$plot
			)); 
		}
		
		return $html;
	}
} 
