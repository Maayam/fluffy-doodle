<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A class representing a media, which is mostly images
 *
 * @ORM\Entity
 * @ORM\Table(name="media")
 */

class Media
{
	/**
	 * @var integer $id The id of the media
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var integer $path The filename of the media
	 *
	 * @ORM\Column(type="string", length=250)
	 */
	private $path;


	/**
	 * @var object $plot The plot associated with the media
	 *
	 * @ORM\ManyToOne(targetEntity="Plot", inversedBy="pictures", cascade={"persist"})
	 * @ORM\JoinColumn(name="plot_id", referencedColumnName="id")
	 *
	 */
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

