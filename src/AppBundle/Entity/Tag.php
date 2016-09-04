<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */

class Tag
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/** @ORM\Column(length=31)
	 */
	private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Plot")
     */
    private $plots;

    public function __construct() {
        $this->plots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    ///////////////////////
    //GETTERS
    ///////////////////////

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    /////////////////////
    //SETTERS
    /////////////////////

    public function setId($id){
        $this->id = $id;
    }

    public function setName($name){
        $this->name = $name;
    }
}