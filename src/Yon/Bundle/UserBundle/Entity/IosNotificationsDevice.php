<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IosNotificationsDevice
 *
 * @ORM\Table(name="ios_notifications_device", uniqueConstraints={@ORM\UniqueConstraint(name="token", columns={"token", "service_id"})}, indexes={@ORM\Index(name="i_service_id_5395eaf868d3ed49_fk_ios_notifications_apnservice_id", columns={"service_id"})})
 * @ORM\Entity
 */
class IosNotificationsDevice
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
     * @ORM\Column(name="token", type="string", length=64, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deactivated_at", type="datetime", nullable=true)
     */
    private $deactivatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime", nullable=false)
     */
    private $addedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_notified_at", type="datetime", nullable=true)
     */
    private $lastNotifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=30, nullable=true)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="display", type="string", length=30, nullable=true)
     */
    private $display;

    /**
     * @var string
     *
     * @ORM\Column(name="os_version", type="string", length=20, nullable=true)
     */
    private $osVersion;

    /**
     * @var \IosNotificationsApnservice
     *
     * @ORM\ManyToOne(targetEntity="IosNotificationsApnservice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     * })
     */
    private $service;



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
     * Set token
     *
     * @param string $token
     * @return IosNotificationsDevice
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return IosNotificationsDevice
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
     * Set deactivatedAt
     *
     * @param \DateTime $deactivatedAt
     * @return IosNotificationsDevice
     */
    public function setDeactivatedAt($deactivatedAt)
    {
        $this->deactivatedAt = $deactivatedAt;

        return $this;
    }

    /**
     * Get deactivatedAt
     *
     * @return \DateTime 
     */
    public function getDeactivatedAt()
    {
        return $this->deactivatedAt;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return IosNotificationsDevice
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addedAt
     *
     * @return \DateTime 
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set lastNotifiedAt
     *
     * @param \DateTime $lastNotifiedAt
     * @return IosNotificationsDevice
     */
    public function setLastNotifiedAt($lastNotifiedAt)
    {
        $this->lastNotifiedAt = $lastNotifiedAt;

        return $this;
    }

    /**
     * Get lastNotifiedAt
     *
     * @return \DateTime 
     */
    public function getLastNotifiedAt()
    {
        return $this->lastNotifiedAt;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return IosNotificationsDevice
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set display
     *
     * @param string $display
     * @return IosNotificationsDevice
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return string 
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set osVersion
     *
     * @param string $osVersion
     * @return IosNotificationsDevice
     */
    public function setOsVersion($osVersion)
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * Get osVersion
     *
     * @return string 
     */
    public function getOsVersion()
    {
        return $this->osVersion;
    }

    /**
     * Set service
     *
     * @param \Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice $service
     * @return IosNotificationsDevice
     */
    public function setService(\Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice 
     */
    public function getService()
    {
        return $this->service;
    }
}
