<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUserprofile
 *
 * @ORM\Table(name="api_userprofile", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id"})}, indexes={@ORM\Index(name="rank", columns={"rank"}), @ORM\Index(name="star", columns={"star"}), @ORM\Index(name="creation", columns={"creation"})})
 * @ORM\Entity(repositoryClass="Yon\Bundle\UserBundle\Repository\ApiUserprofileRepository")
 */
class ApiUserprofile
{
    static $USER_TYPE = array (
                    0 => 'Joueur',
                    1 => 'Super Administrateur',
                    2 => 'Admin Interne',
                    3 => 'Admin Externe',
                    4 => 'Partenaire Commercial',
    );
    
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
     * @ORM\Column(name="phone_number", type="string", length=100, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="balance", type="integer", nullable=false)
     */
    private $balance;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=100, nullable=true)
     */
    private $picture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=false)
     */
    private $valid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_connection", type="datetime", nullable=true)
     */
    private $firstConnection;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=12, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="fallback_name", type="string", length=100, nullable=true)
     */
    private $fallbackName;

    /**
     * @var string
     *
     * @ORM\Column(name="invite_mode", type="string", length=32, nullable=true)
     */
    private $inviteMode;

    /**
     * @var integer
     *
     * @ORM\Column(name="followers_count", type="integer", nullable=false)
     */
    private $followersCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="followings_count", type="integer", nullable=false)
     */
    private $followingsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="display_username", type="string", length=30, nullable=true)
     */
    private $displayUsername;

    /**
     * @var boolean
     *
     * @ORM\Column(name="star", type="boolean", nullable=false)
     */
    private $star;

    /**
     * @var integer
     *
     * @ORM\Column(name="challenges_count", type="integer", nullable=false)
     */
    private $challengesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint", nullable=false)
     */
    private $state;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=false)
     */
    private $type = 0;

    /**
     * @var \AuthUser
     *
     * @ORM\ManyToOne(targetEntity="AuthUser", cascade ={"persist"})
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
     * @return ApiUserprofile
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return ApiUserprofile
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     * @return ApiUserprofile
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return ApiUserprofile
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return ApiUserprofile
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return ApiUserprofile
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set firstConnection
     *
     * @param \DateTime $firstConnection
     * @return ApiUserprofile
     */
    public function setFirstConnection($firstConnection)
    {
        $this->firstConnection = $firstConnection;

        return $this;
    }

    /**
     * Get firstConnection
     *
     * @return \DateTime 
     */
    public function getFirstConnection()
    {
        return $this->firstConnection;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return ApiUserprofile
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set fallbackName
     *
     * @param string $fallbackName
     * @return ApiUserprofile
     */
    public function setFallbackName($fallbackName)
    {
        $this->fallbackName = $fallbackName;

        return $this;
    }

    /**
     * Get fallbackName
     *
     * @return string 
     */
    public function getFallbackName()
    {
        return $this->fallbackName;
    }

    /**
     * Set inviteMode
     *
     * @param string $inviteMode
     * @return ApiUserprofile
     */
    public function setInviteMode($inviteMode)
    {
        $this->inviteMode = $inviteMode;

        return $this;
    }

    /**
     * Get inviteMode
     *
     * @return string 
     */
    public function getInviteMode()
    {
        return $this->inviteMode;
    }

    /**
     * Set followersCount
     *
     * @param integer $followersCount
     * @return ApiUserprofile
     */
    public function setFollowersCount($followersCount)
    {
        $this->followersCount = $followersCount;

        return $this;
    }

    /**
     * Get followersCount
     *
     * @return integer 
     */
    public function getFollowersCount()
    {
        return $this->followersCount;
    }

    /**
     * Set followingsCount
     *
     * @param integer $followingsCount
     * @return ApiUserprofile
     */
    public function setFollowingsCount($followingsCount)
    {
        $this->followingsCount = $followingsCount;

        return $this;
    }

    /**
     * Get followingsCount
     *
     * @return integer 
     */
    public function getFollowingsCount()
    {
        return $this->followingsCount;
    }

    /**
     * Set displayUsername
     *
     * @param string $displayUsername
     * @return ApiUserprofile
     */
    public function setDisplayUsername($displayUsername)
    {
        $this->displayUsername = $displayUsername;

        return $this;
    }

    /**
     * Get displayUsername
     *
     * @return string 
     */
    public function getDisplayUsername()
    {
        return $this->displayUsername;
    }

    /**
     * Set star
     *
     * @param boolean $star
     * @return ApiUserprofile
     */
    public function setStar($star)
    {
        $this->star = $star;

        return $this;
    }

    /**
     * Get star
     *
     * @return boolean 
     */
    public function getStar()
    {
        return $this->star;
    }

    /**
     * Set challengesCount
     *
     * @param integer $challengesCount
     * @return ApiUserprofile
     */
    public function setChallengesCount($challengesCount)
    {
        $this->challengesCount = $challengesCount;

        return $this;
    }

    /**
     * Get challengesCount
     *
     * @return integer 
     */
    public function getChallengesCount()
    {
        return $this->challengesCount;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return ApiUserprofile
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Set type
     *
     * @param integer $type
     * @return ApiUserprofile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
    * Get status
    *
    * @return interger
    */
    public function getTypeString() {

        return self::$USER_TYPE[$this->type];
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUserprofile
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
