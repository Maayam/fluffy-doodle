<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performance
 *
 * @ORM\Table(name="performance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerformanceRepository")
 */
class Performance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube", type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @var string
     *
     * @ORM\Column(name="niconicoDouga", type="string", length=255, nullable=true)
     */
    private $niconicoDouga;

    /**
     * @var string
     *
     * @ORM\Column(name="biribiri", type="string", length=255, nullable=true)
     */
    private $biribiri;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="datetime", nullable=true)
     */
    private $dateAdded;

    /**
     * @var object $performer The user who performed this performance
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="performances", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    private $performer;

    /**
     * @var object $plot The plot where performance took place
     *
     * @ORM\ManyToOne(targetEntity="Plot", inversedBy="performances", cascade={"persist"})
     * @ORM\JoinColumn(name="plot_id", referencedColumnName="id")
     *
     */
    private $plot;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set youtube
     *
     * @param string $youtube
     *
     * @return Performance
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set niconicoDouga
     *
     * @param string $niconicoDouga
     *
     * @return Performance
     */
    public function setNiconicoDouga($niconicoDouga)
    {
        $this->niconicoDouga = $niconicoDouga;

        return $this;
    }

    /**
     * Get niconicoDouga
     *
     * @return string
     */
    public function getNiconicoDouga()
    {
        return $this->niconicoDouga;
    }

    /**
     * Set biribiri
     *
     * @param string $biribiri
     *
     * @return Performance
     */
    public function setBiribiri($biribiri)
    {
        $this->biribiri = $biribiri;

        return $this;
    }

    /**
     * Get biribiri
     *
     * @return string
     */
    public function getBiribiri()
    {
        return $this->biribiri;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Performance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set performer
     *
     * @param \AppBundle\Entity\User $performer
     *
     * @return Performance
     */
    public function setPerformer(\AppBundle\Entity\User $performer = null)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return \AppBundle\Entity\User
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Set plot
     *
     * @param \AppBundle\Entity\Plot $plot
     *
     * @return Performance
     */
    public function setPlot(\AppBundle\Entity\Plot $plot = null)
    {
        $this->plot = $plot;

        return $this;
    }

    /**
     * Get plot
     *
     * @return \AppBundle\Entity\Plot
     */
    public function getPlot()
    {
        return $this->plot;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Performance
     */
    public function setdateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getdateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Performance
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
