<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagController extends Controller
{
	/**
	 *@Route("/tag/search", name="findTag")
	 *@Method({"GET"})
	 */
	public function findTags(Request $request){
		$filter = $request->query->get('filter');
		$term = $request->query->get('search');
		$tags = $this->findByName($term);

		//if tags is empty?
		return new JsonResponse($tags);
	}

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
