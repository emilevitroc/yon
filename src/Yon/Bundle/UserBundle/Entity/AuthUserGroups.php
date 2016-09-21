<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthUserGroups
 *
 * @ORM\Table(name="auth_user_groups", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id", "group_id"})}, indexes={@ORM\Index(name="auth_user_groups_group_id_33ac548dcf5f8e37_fk_auth_group_id", columns={"group_id"}), @ORM\Index(name="IDX_592D50D5A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class AuthUserGroups
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
     * @var \AuthGroup
     *
     * @ORM\ManyToOne(targetEntity="AuthGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $group;

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
     * Set group
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthGroup $group
     * @return AuthUserGroups
     */
    public function setGroup(\Yon\Bundle\UserBundle\Entity\AuthGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Yon\Bundle\UserBundle\Entity\AuthGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return AuthUserGroups
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
