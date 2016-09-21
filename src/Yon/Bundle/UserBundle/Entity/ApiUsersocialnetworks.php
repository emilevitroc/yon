<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUsersocialnetworks
 *
 * @ORM\Table(name="api_usersocialnetworks", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiUsersocialnetworks
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
     * @ORM\Column(name="facebook_token", type="string", length=256, nullable=true)
     */
    private $facebookToken;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_user_id", type="string", length=64, nullable=true)
     */
    private $facebookUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_user_id", type="string", length=64, nullable=true)
     */
    private $twitterUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_access_token", type="string", length=256, nullable=true)
     */
    private $twitterAccessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_access_token_secret", type="string", length=256, nullable=true)
     */
    private $twitterAccessTokenSecret;

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
     * Set facebookToken
     *
     * @param string $facebookToken
     * @return ApiUsersocialnetworks
     */
    public function setFacebookToken($facebookToken)
    {
        $this->facebookToken = $facebookToken;

        return $this;
    }

    /**
     * Get facebookToken
     *
     * @return string 
     */
    public function getFacebookToken()
    {
        return $this->facebookToken;
    }

    /**
     * Set facebookUserId
     *
     * @param string $facebookUserId
     * @return ApiUsersocialnetworks
     */
    public function setFacebookUserId($facebookUserId)
    {
        $this->facebookUserId = $facebookUserId;

        return $this;
    }

    /**
     * Get facebookUserId
     *
     * @return string 
     */
    public function getFacebookUserId()
    {
        return $this->facebookUserId;
    }

    /**
     * Set twitterUserId
     *
     * @param string $twitterUserId
     * @return ApiUsersocialnetworks
     */
    public function setTwitterUserId($twitterUserId)
    {
        $this->twitterUserId = $twitterUserId;

        return $this;
    }

    /**
     * Get twitterUserId
     *
     * @return string 
     */
    public function getTwitterUserId()
    {
        return $this->twitterUserId;
    }

    /**
     * Set twitterAccessToken
     *
     * @param string $twitterAccessToken
     * @return ApiUsersocialnetworks
     */
    public function setTwitterAccessToken($twitterAccessToken)
    {
        $this->twitterAccessToken = $twitterAccessToken;

        return $this;
    }

    /**
     * Get twitterAccessToken
     *
     * @return string 
     */
    public function getTwitterAccessToken()
    {
        return $this->twitterAccessToken;
    }

    /**
     * Set twitterAccessTokenSecret
     *
     * @param string $twitterAccessTokenSecret
     * @return ApiUsersocialnetworks
     */
    public function setTwitterAccessTokenSecret($twitterAccessTokenSecret)
    {
        $this->twitterAccessTokenSecret = $twitterAccessTokenSecret;

        return $this;
    }

    /**
     * Get twitterAccessTokenSecret
     *
     * @return string 
     */
    public function getTwitterAccessTokenSecret()
    {
        return $this->twitterAccessTokenSecret;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUsersocialnetworks
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
