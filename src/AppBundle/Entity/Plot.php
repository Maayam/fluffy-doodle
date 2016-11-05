<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * A class representing a dancing spot
 *
 * @ORM\Entity
 * @ORM\Table(name="plot")
 */

class Plot
{
	/**
	 * @var integer $id The id of the plot
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/** 
	 * @var float $lat Latitude of the plot
	 *
	 * @ORM\Column(type="decimal", precision=10, scale=6)
	 */
	private $lat;

	/** 
	 * @var float $lng Longitude of the plot
	 *
	 * @ORM\Column(type="decimal", precision=10, scale=6)
	 */
	private $lng;

	/**
	 * @var string $name The name of the plot
	 *
	 * @ORM\Column(length=63)
	 */
	private $name;

	/** 
	 * @var string $note The description of the plot
	 **
	 * @ORM\Column(type="text")
	 */
	private $note;

	/**
	 * @ORM\ManyToMany(targetEntity="Tag", inversedBy="plots", cascade={"persist", "merge"})
	 * @ORM\JoinTable(
	 *	joinColumns={@ORM\JoinColumn(name="Plot_idPlot", referencedColumnName="id")},
	 *	inverseJoinColumns={@ORM\JoinColumn(name="Tag_idTag", referencedColumnName="id")}
	 * )
	 * @var array $tags Tags associated with the plot
	 */
	private $tags;

	/**
	 * @var resource $file A value used to upload an image
	 */
	private $file;

	/**
	 * @var array $pictures Picture associated with the plot
	 *
	 * @ORM\OneToMany(targetEntity="Media", mappedBy="plot")
	 */
	private $pictures;

	/**
	 * @var array $performances Performances associated with the plot
	 *
	 * @ORM\OneToMany(targetEntity="Performance", mappedBy="plot")
	 */
	private $performances;

	public function __construct() {
		$this->tags = new ArrayCollection();
		$this->pictures = new ArrayCollection();
		$this->performances = new ArrayCollection();
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

	public function getPictures() {
		return $this->pictures;
	}
	
	public function getFile() {
		return $this->file;
	}
	
	public function getTags() {
		return $this->tags;
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

	public function setPictures($pictures) {
		$this->picture = $pictures;
	}

	public function addPicture($picture) {
		$this->pictures[] = $picture;
	}

	public function setFile($file) {
		$this->file = $file;
	}

	public function addTag($tag) {
		if(!$this->tags->contains($tag))
			$this->tags->add($tag);	
	}

	public function setTags($tags) {
		if($tags instanceof ArrayCollection || is_array($tags)) {
			foreach($tags as $tag) {
				$this->addTag($tag);
			}
		} elseif($tags instanceof Tag) {
			$this->addTag($tags);
		} else {
			throw new Exception("$tags must be an instance of Tag or ArrayCollection ");
		}
	}

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Remove picture
     *
     * @param \AppBundle\Entity\Media $picture
     */
    public function removePicture(\AppBundle\Entity\Media $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Add performance
     *
     * @param \AppBundle\Entity\Performance $performance
     *
     * @return Plot
     */
    public function addPerformance(\AppBundle\Entity\Performance $performance)
    {
        $this->performances[] = $performance;

        return $this;
    }

    /**
     * Remove performance
     *
     * @param \AppBundle\Entity\Performance $performance
     */
    public function removePerformance(\AppBundle\Entity\Performance $performance)
    {
        $this->performances->removeElement($performance);
    }

    /**
     * Get performances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerformances()
    {
        return $this->performances;
    }
}
