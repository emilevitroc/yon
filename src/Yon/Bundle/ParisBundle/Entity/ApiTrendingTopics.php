<?php

namespace Yon\Bundle\ParisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiHashtag
 *
 * @ORM\Table(name="api_trendingtopic", indexes={@ORM\Index(name="visible", columns={"visible"})})
 * @ORM\Entity
 */
class ApiTrendingTopics
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=100, nullable=false)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=false)
     */
    private $location;

    /**
     * @var float
     *
     * @ORM\Column(name="`range`", type="float", precision=10, scale=0, nullable=true)
     */
    private $range;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    private $visible;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setCreation ( new \DateTime () );
        $this->setLocation('0.0,0.0');
        $this->setVisible(1);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     * @return ApiHashtag
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime 
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return ApiHashtag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return ApiHashtag
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set range
     *
     * @param float $range
     * @return ApiHashtag
     */
    public function setRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Get range
     *
     * @return float 
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return ApiHashtag
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }
}
