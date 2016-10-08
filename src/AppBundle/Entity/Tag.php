<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A class representing a Tag
 *
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */

class Tag
{
    /**
     * @var integer $id The id of the tag
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	private $id;

	/** 
	 * @var string $name The name of the tag
	 *
	 * @ORM\Column(length=31)
	 */
	private $name;

    /**
     * @var array $plots Plots associated with the tag
     *
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
