<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 */

class Media
{
    	/**
     	 * @ORM\Column(type="integer")
     	 * @ORM\Id
     	 * @ORM\GeneratedValue(strategy="AUTO")
     	 **/
	private $id;

	/**
	 * @ORM\Column(type="string", length=250)
	 **/
	private $path;


	/**
	 * @ORM\ManyToOne(targetEntity="Plot", inversedBy="pictures", cascade={"persist"})
	 * @ORM\JoinColumn(name="plot_id", referencedColumnName="id")
	 **/
	private $plot;

	///////////////////////
	//GETTERS
	///////////////////////

	public function getId(){
		return $this->id;
	}

	public function getPath() {
		return $this->path;
	}

	public function getPlot() {
		return $this->path;
	}

	/////////////////////
	//SETTERS
	/////////////////////

	public function setId($id){
		$this->id = $id;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function setPlot($plot) {
		$this->plot = $plot;
	}
}

