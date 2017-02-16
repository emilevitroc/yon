<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiCoupon
 *
 * @ORM\Table(name="api_coupon", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="type", columns={"type"})})
 * @ORM\Entity
 */
class ApiCoupon
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="short_title", type="string", length=100, nullable=false)
     */
    private $shortTitle;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="used_date", type="datetime", nullable=true)
     */
    private $usedDate;

    /**
     * @var \AuthUser
     *
     * @ORM\ManyToOne(targetEntity="AuthUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

        /**
     * @var \Challenge
     *
     * @ORM\ManyToOne(targetEntity="ApiChallenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

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
     * Set type
     *
     * @param string $type
     * @return ApiCoupon
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return ApiCoupon
     */
    public function setTitle($title)
    {
        $this->type = $title;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set shorttitle
     *
     * @param string $shortTitle
     * @return ApiCoupon
     */
    public function setShortTitle($shortTitle)
    {
        $this->type = $shortTitle;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return ApiCoupon
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     * @return ApiCoupon
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
     * Set usedDate
     *
     * @param \DateTime $usedDate
     * @return ApiCoupon
     */
    public function setUsedDate($usedDate)
    {
        $this->usedDate = $usedDate;

        return $this;
    }

    /**
     * Get usedDate
     *
     * @return \DateTime 
     */
    public function getUsedDate()
    {
        return $this->usedDate;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiCoupon
     */
    public function setUser(\Yon\Bundle\UserBundle\Entity\AuthUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Yon\Bundle\UserBundle\Entity\AuthUser 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $challenge
     * @return ApiCoupon
     */
    public function setChallenge(\Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \Yon\Bundle\ParisBundle\Entity\ApiChallenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }
    
}
