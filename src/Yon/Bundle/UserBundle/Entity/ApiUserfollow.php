<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUserfollow
 *
 * @ORM\Table(name="api_userfollow", uniqueConstraints={@ORM\UniqueConstraint(name="api_userfollow_user_id_2cef4afbd9d4e1f_uniq", columns={"user_id", "followed_user_id"})}, indexes={@ORM\Index(name="api_userfollow_followed_user_id_69104af7cc687916_fk_auth_user_id", columns={"followed_user_id"}), @ORM\Index(name="IDX_C7642FA5A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiUserfollow
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
     * @var \AuthUser
     *
     * @ORM\ManyToOne(targetEntity="AuthUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="followed_user_id", referencedColumnName="id")
     * })
     */
    private $followedUser;

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
     * @return ApiUserfollow
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
     * Set followedUser
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $followedUser
     * @return ApiUserfollow
     */
    public function setFollowedUser(\Yon\Bundle\UserBundle\Entity\AuthUser $followedUser = null)
    {
        $this->followedUser = $followedUser;

        return $this;
    }

    /**
     * Get followedUser
     *
     * @return \Yon\Bundle\UserBundle\Entity\AuthUser 
     */
    public function getFollowedUser()
    {
        return $this->followedUser;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUserfollow
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
