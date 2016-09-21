<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthUserUserPermissions
 *
 * @ORM\Table(name="auth_user_user_permissions", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id", "permission_id"})}, indexes={@ORM\Index(name="auth_user_u_permission_id_384b62483d7071f0_fk_auth_permission_id", columns={"permission_id"}), @ORM\Index(name="IDX_C0E9A7C0A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class AuthUserUserPermissions
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
     * @var \AuthPermission
     *
     * @ORM\ManyToOne(targetEntity="AuthPermission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     * })
     */
    private $permission;

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
     * Set permission
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthPermission $permission
     * @return AuthUserUserPermissions
     */
    public function setPermission(\Yon\Bundle\UserBundle\Entity\AuthPermission $permission = null)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return \Yon\Bundle\UserBundle\Entity\AuthPermission 
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return AuthUserUserPermissions
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
