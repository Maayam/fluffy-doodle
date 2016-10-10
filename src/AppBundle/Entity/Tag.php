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
	 * @ORM\Column(length=31, unique=true)
	 */
	private $name;

    /**
     * @var array $plots Plots associated with the tag
     *
     * @ORM\ManyToMany(targetEntity="Plot", mappedBy="tags", cascade={"persist", "merge"})
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

	public function getPlots() {
		return $this->plots;
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
 
 	public function addPlot($plot) {
 		if(!$this->plots->contains($plot)) {
 			if(!$plot->getTags()->contains($this)) {
 				$plot->addTag($this);
 			}
 			$this->plot->add($plot);
 		}
 	}   
    public function setPlot($plots) {
    	if($plots instanceof ArrayCollection || is_array($plots)) {
    		foreach($plots as $plot) {
    			$this->addPlot($plot);
    		}
    	} elseif($plots instanceof Plot) {
    		$this->addPlot($plots);
    	} else {
    		throw new Exception("$plots must be an instance of Plot or ArrayCollection");
    	}
    }
}
