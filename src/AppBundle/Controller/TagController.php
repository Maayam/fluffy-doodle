<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * A class managing tags
 */
class TagController extends Controller
{

	/**
	 * Render a tag page, listing all plot related to this tag
	 *
	 * @param $request The HTML request
	 * @param $id The id of the tag to render
	 * @return The rendered page
	 *
	 * @Route("/view/tag/{id}", name="viewTag", defaults={"_locale"="en"})
	 * @Route("/{_locale}/view/tag/{id}", name="viewTagLoc", requirements={"_locale"="en|fr"})
	 * @Method({"GET"})
	 */
	public function viewTag(Request $request, $id) {
		$em = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\Tag');
		
		$tag = $em->findOneById($id);
		
		if($request->isXmlHttpRequest()) {
			$offset = $request->query->get("offset");
			
			$limit = $offset + 10;
			$max = count($tag->getPlots());

			if($offset > $max)
				return new JsonResponse(array('success'=>true, 'html'=>"", 'end'=>true));

			if($limit > $max)
				$limit = $max;
			
			$html = $this->renderView('/page/tagViewPlots.html.twig', array("plots"=>$tag->getPlots(),
																			"offset"=>$offset,
																			"limit"=>$limit));
			return new JsonResponse(array('success'=>true, 'html'=>$html));
		} else {
			return $this->render('page/tagView.html.twig', array("tag"=>$tag));
		}
	}
		
	/**
	 * Router for finding tags
	 *
	 * @param $request The HTML request
	 * @return JSON representation of the tags matching the research
	 *
	 * @Route("/tag/search", name="findTag", defaults={"_locale"="en"})
	 * @Route("/{_locale}/tag/search", name="findTagLoc", requirements={"_locale"="en|fr"})
	 * @Method({"GET"})
	 */
	public function findTags(Request $request){
		$filter = $request->query->get('filter');
		$term = $request->query->get('search');
		$tags = $this->findByName($term);

		//if tags is empty?
		return new JsonResponse($tags);
	}

	/**
	 * Find all tags matching with a name
	 *
	 * @param $name The name to match with
	 * @return All matching tags
	 */
	private function findByName($name) {
	//returns all plots which match with a name
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
			"SELECT tag.id, tag.name ".
			"FROM AppBundle\Entity\Tag tag ".
			"WHERE tag.name LIKE :name "
		)->setParameters(array('name' => "%". $name."%"));

		$tags = $query->getResult();
		return $tags;
	}
}
