<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUsergroupmembership
 *
 * @ORM\Table(name="api_usergroupmembership", uniqueConstraints={@ORM\UniqueConstraint(name="api_usergroupmembership_user_id_26f7fe2386fde098_uniq", columns={"user_id", "user_group_id"})}, indexes={@ORM\Index(name="api_usergroupmembership_5296237d", columns={"user_group_id"}), @ORM\Index(name="IDX_135EDD6EA76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiUsergroupmembership
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
     * @ORM\Column(name="notification_mode", type="string", length=16, nullable=false)
     */
    private $notificationMode;

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
     * @var \ApiUsergroup
     *
     * @ORM\ManyToOne(targetEntity="ApiUsergroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     * })
     */
    private $userGroup;



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
     * @return ApiUsergroupmembership
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
     * Set notificationMode
     *
     * @param string $notificationMode
     * @return ApiUsergroupmembership
     */
    public function setNotificationMode($notificationMode)
    {
        $this->notificationMode = $notificationMode;

        return $this;
    }

    /**
     * Get notificationMode
     *
     * @return string 
     */
    public function getNotificationMode()
    {
        return $this->notificationMode;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUsergroupmembership
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
     * Set userGroup
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiUsergroup $userGroup
     * @return ApiUsergroupmembership
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
}
