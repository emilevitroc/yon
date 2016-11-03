<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;
/**
 * AuthUser
 *
 * @ORM\Table(name="auth_user", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})})
 * @ORM\Entity
 */
class AuthUser
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
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_superuser", type="boolean", nullable=false)
     */
    private $isSuperuser;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=30, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_staff", type="boolean", nullable=false)
     */
    private $isStaff;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_joined", type="datetime", nullable=false)
     */
    private $dateJoined;
    
    /**
     * @var \AuthUser
     *
     * @ORM\OneToOne(targetEntity="ApiUserprofile", cascade ={"persist"}, mappedBy="user")
     */
    private $user;
    

    
    /**
     * @ORM\OneToMany(targetEntity="\Yon\Bundle\ParisBundle\Entity\ApiChallenge", mappedBy="user")
     */
    private $challenge;
    
    /**
     * @var \ApiContestChallenge
     *
     * @ORM\OneToMany(targetEntity="\Yon\Bundle\ParisBundle\Entity\ApiBet", mappedBy="user")
     */
    private $bets;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->challenge = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bets = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add challenge
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge
     * @return ApiChallenge
     */
    public function addChallenge(\Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge)
    {
        $this->challenge[] = $challenge;

        return $this;
    }

    /**
     * Remove challenge
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge
     */
    public function removeChallenge(\Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge)
    {
        $this->challenge->removeElement($challenge);
    }

    /**
     * Get challenge
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }
    
    /**
     * Add bets
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiBet $bets
     * @return ApiBet
     */
    public function addBet(\Yon\Bundle\ParisBundle\Entity\ApiBet $bets)
    {
        $this->bets[] = $bets;

        return $this;
    }

    /**
     * Remove bets
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiBet $bets
     */
    public function removeBet(\Yon\Bundle\ParisBundle\Entity\ApiBet $bets)
    {
        $this->bets->removeElement($bets);
    }

    /**
     * Get bets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBet()
    {
        return $this->bets;
    }
    
    public function isHavedBanedChallenge(){
        $isBaned = false;
        foreach ($this->challenge as $challenge){
            if($challenge->getState() == 3) { //bannir
                $isBaned = true;
                break;
            }
        }
        return $isBaned;
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
     * Set password
     *
     * @param string $password
     * @return AuthUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return AuthUser
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set isSuperuser
     *
     * @param boolean $isSuperuser
     * @return AuthUser
     */
    public function setIsSuperuser($isSuperuser)
    {
        $this->isSuperuser = $isSuperuser;

        return $this;
    }

    /**
     * Get isSuperuser
     *
     * @return boolean 
     */
    public function getIsSuperuser()
    {
        return $this->isSuperuser;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return AuthUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return AuthUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return AuthUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AuthUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isStaff
     *
     * @param boolean $isStaff
     * @return AuthUser
     */
    public function setIsStaff($isStaff)
    {
        $this->isStaff = $isStaff;

        return $this;
    }

    /**
     * Get isStaff
     *
     * @return boolean 
     */
    public function getIsStaff()
    {
        return $this->isStaff;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return AuthUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set dateJoined
     *
     * @param \DateTime $dateJoined
     * @return AuthUser
     */
    public function setDateJoined($dateJoined)
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    /**
     * Get dateJoined
     *
     * @return \DateTime 
     */
    public function getDateJoined()
    {
        return $this->dateJoined;
    }
    
    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiUserprofile $user
     * @return ApiUserprofile
     */
    public function setUser(\Yon\Bundle\UserBundle\Entity\ApiUserprofile $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiUserprofile 
     */
    public function getUser()
    {
        return $this->user;
    }
    
}
