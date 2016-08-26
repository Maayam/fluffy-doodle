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

	///////////////////////
	//GETTERS
	///////////////////////

	public function get_id(){
		return $this->id;
	}

	public function get_lat(){
		return $this->lat;
	}

	public function get_lng(){
		return $this->lng;
	}

	public function get_name(){
		return $this->name;
	}

	public function get_note(){
		return $this->note;
	}

	/////////////////////
	//SETTERS
	/////////////////////

	public function set_id($id){
		$this->id = $id;
	}

	public function set_lat($lat){
		$this->lat = $lat;
	}

	public function set_lng($lng){
		$this->lng = $lng;
	}

	public function set_name($name){
		$this->name = $name;
	}

	public function set_note($note){
		$this->note = $note;
	}
}