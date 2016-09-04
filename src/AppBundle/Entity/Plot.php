<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plot")
 */

class Plot
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/** @ORM\Column(type="decimal", precision=10, scale=6)
	 */
	private $lat;

	/** @ORM\Column(type="decimal", precision=10, scale=6)
	 */
	private $lng;

	/** @ORM\Column(length=63)
	 */
	private $name;

	/** @ORM\Column(type="text")
	 */
	private $note;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    private $tags;

    public function __construct() {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

	///////////////////////
	//GETTERS
	///////////////////////

	public function getId(){
		return $this->id;
	}

	public function getLat(){
		return $this->lat;
	}

	public function getLng(){
		return $this->lng;
	}

	public function getName(){
		return $this->name;
	}

	public function getNote(){
		return $this->note;
	}

	/////////////////////
	//SETTERS
	/////////////////////

	public function setId($id){
		$this->id = $id;
	}

	public function setLat($lat){
		$this->lat = $lat;
	}

	public function setLng($lng){
		$this->lng = $lng;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function setNote($note){
		$this->note = $note;
	}
}