<?php

namespace Yon\Bundle\ParisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiHashtag
 *
 * @ORM\Table(name="api_featuredhashtag", indexes={@ORM\Index(name="visible", columns={"visible"})})
 * @ORM\Entity
 */
class ApiFeaturedhashtag
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
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", length=10, nullable=false)
     */
    private $position;

     /**
     * @var \ApiHashtag
     *
     * @ORM\ManyToOne(targetEntity="ApiHashtag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hashtag_id", referencedColumnName="id")
     * })
     */
    private $hashtag;
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setCreation ( new \DateTime () );
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
     * Set position
     *
     * @param integer $position
     * @return ApiHashtag
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }
    
    /**
     * Set hashtagId
     *
     * @param integer $hashtag
     * @return ApiFeaturedhashtag
     */
    public function setHashtagId(ApiHashtag $hashtag)
    {
        $this->position = $hashtag;

        return $this;
    }

    /**
     * Get hashtagId
     *
     * @return integer 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }
    
    
}
