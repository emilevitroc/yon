<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IosNotificationsDeviceUsers
 *
 * @ORM\Table(name="ios_notifications_device_users", uniqueConstraints={@ORM\UniqueConstraint(name="device_id", columns={"device_id", "user_id"})}, indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="IDX_39B3BB0094A4C7D4", columns={"device_id"})})
 * @ORM\Entity
 */
class IosNotificationsDeviceUsers
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
     * @var \IosNotificationsDevice
     *
     * @ORM\ManyToOne(targetEntity="IosNotificationsDevice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     * })
     */
    private $device;

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
     * Set device
     *
     * @param \Yon\Bundle\UserBundle\Entity\IosNotificationsDevice $device
     * @return IosNotificationsDeviceUsers
     */
    public function setDevice(\Yon\Bundle\UserBundle\Entity\IosNotificationsDevice $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \Yon\Bundle\UserBundle\Entity\IosNotificationsDevice 
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return IosNotificationsDeviceUsers
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
