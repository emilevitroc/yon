<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiNotification
 *
 * @ORM\Table(name="api_notification", indexes={@ORM\Index(name="api_notification_903d3026", columns={"challenge_id"}), @ORM\Index(name="api_notification_5296237d", columns={"user_group_id"}), @ORM\Index(name="api_notification_e4858d5c", columns={"hashtag_id"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="status", columns={"status"}), @ORM\Index(name="api_notification_user_id_58ecc9f4cc337fc5_fk_auth_user_id", columns={"user_id"}), @ORM\Index(name="api_notification_8a611301", columns={"concerning_user_id"})})
 * @ORM\Entity
 */
class ApiNotification
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
     * @var boolean
     *
     * @ORM\Column(name="read", type="boolean", nullable=false)
     */
    private $read;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="balance_before", type="integer", nullable=true)
     */
    private $balanceBefore;

    /**
     * @var integer
     *
     * @ORM\Column(name="balance_after", type="integer", nullable=true)
     */
    private $balanceAfter;

    /**
     * @var boolean
     *
     * @ORM\Column(name="silent", type="boolean", nullable=false)
     */
    private $silent;

    /**
     * @var string
     *
     * @ORM\Column(name="message_data", type="string", length=500, nullable=true)
     */
    private $messageData;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=128, nullable=true)
     */
    private $link;

    /**
     * @var \AuthUser
     *
     * @ORM\ManyToOne(targetEntity="AuthUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concerning_user_id", referencedColumnName="id")
     * })
     */
    private $concerningUser;

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
     * @var \ApiChallenge
     *
     * @ORM\ManyToOne(targetEntity="ApiChallenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var \ApiUsergroup
     *
     * @ORM\ManyToOne(targetEntity="ApiUsergroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     * })
     */
    private $userGroup;

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
     * @return ApiNotification
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
     * Set read
     *
     * @param boolean $read
     * @return ApiNotification
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean 
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ApiNotification
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
     * Set balanceBefore
     *
     * @param integer $balanceBefore
     * @return ApiNotification
     */
    public function setBalanceBefore($balanceBefore)
    {
        $this->balanceBefore = $balanceBefore;

        return $this;
    }

    /**
     * Get balanceBefore
     *
     * @return integer 
     */
    public function getBalanceBefore()
    {
        return $this->balanceBefore;
    }

    /**
     * Set balanceAfter
     *
     * @param integer $balanceAfter
     * @return ApiNotification
     */
    public function setBalanceAfter($balanceAfter)
    {
        $this->balanceAfter = $balanceAfter;

        return $this;
    }

    /**
     * Get balanceAfter
     *
     * @return integer 
     */
    public function getBalanceAfter()
    {
        return $this->balanceAfter;
    }

    /**
     * Set silent
     *
     * @param boolean $silent
     * @return ApiNotification
     */
    public function setSilent($silent)
    {
        $this->silent = $silent;

        return $this;
    }

    /**
     * Get silent
     *
     * @return boolean 
     */
    public function getSilent()
    {
        return $this->silent;
    }

    /**
     * Set messageData
     *
     * @param string $messageData
     * @return ApiNotification
     */
    public function setMessageData($messageData)
    {
        $this->messageData = $messageData;

        return $this;
    }

    /**
     * Get messageData
     *
     * @return string 
     */
    public function getMessageData()
    {
        return $this->messageData;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ApiNotification
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

    /**
     * Set status
     *
     * @param integer $status
     * @return ApiNotification
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return ApiNotification
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set concerningUser
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $concerningUser
     * @return ApiNotification
     */
    public function setConcerningUser(\Yon\Bundle\UserBundle\Entity\AuthUser $concerningUser = null)
    {
        $this->concerningUser = $concerningUser;

        return $this;
    }

    /**
     * Get concerningUser
     *
     * @return \Yon\Bundle\UserBundle\Entity\AuthUser 
     */
    public function getConcerningUser()
    {
        return $this->concerningUser;
    }

    /**
     * Set hashtag
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiHashtag $hashtag
     * @return ApiNotification
     */
    public function setHashtag(\Yon\Bundle\UserBundle\Entity\ApiHashtag $hashtag = null)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiHashtag 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }

    /**
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge
     * @return ApiNotification
     */
    public function setChallenge(\Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiChallenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set userGroup
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiUsergroup $userGroup
     * @return ApiNotification
     */
    public function setUserGroup(\Yon\Bundle\UserBundle\Entity\ApiUsergroup $userGroup = null)
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * Get userGroup
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiUsergroup 
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiNotification
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
}
