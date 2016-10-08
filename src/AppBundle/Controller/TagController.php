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
	 * Router for finding tags
	 *
	 * @param $request The HTML request
	 * @return JSON representation of the tags matching the research
	 *
	 * @Route("/tag/search", name="findTag")
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
