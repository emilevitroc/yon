<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthGroupPermissions
 *
 * @ORM\Table(name="auth_group_permissions", uniqueConstraints={@ORM\UniqueConstraint(name="group_id", columns={"group_id", "permission_id"})}, indexes={@ORM\Index(name="auth_group__permission_id_1f49ccbbdc69d2fc_fk_auth_permission_id", columns={"permission_id"}), @ORM\Index(name="IDX_18D7041FFE54D947", columns={"group_id"})})
 * @ORM\Entity
 */
class AuthGroupPermissions
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
     * @var \AuthGroup
     *
     * @ORM\ManyToOne(targetEntity="AuthGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $group;



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
     * @return AuthGroupPermissions
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
     * Set group
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthGroup $group
     * @return AuthGroupPermissions
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
}
