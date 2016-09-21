<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IosNotificationsNotification
 *
 * @ORM\Table(name="ios_notifications_notification", indexes={@ORM\Index(name="i_service_id_330699bac5681b46_fk_ios_notifications_apnservice_id", columns={"service_id"})})
 * @ORM\Entity
 */
class IosNotificationsNotification
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
     * @ORM\Column(name="message", type="string", length=200, nullable=false)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge", type="integer", nullable=true)
     */
    private $badge;

    /**
     * @var boolean
     *
     * @ORM\Column(name="silent", type="boolean", nullable=true)
     */
    private $silent;

    /**
     * @var string
     *
     * @ORM\Column(name="sound", type="string", length=30, nullable=false)
     */
    private $sound;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_sent_at", type="datetime", nullable=true)
     */
    private $lastSentAt;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_payload", type="string", length=240, nullable=false)
     */
    private $customPayload;

    /**
     * @var string
     *
     * @ORM\Column(name="loc_payload", type="string", length=240, nullable=false)
     */
    private $locPayload;

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
     * Set message
     *
     * @param string $message
     * @return IosNotificationsNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set badge
     *
     * @param integer $badge
     * @return IosNotificationsNotification
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return integer 
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set silent
     *
     * @param boolean $silent
     * @return IosNotificationsNotification
     */
    public function setSilent($silent)
    {
        $this->silent = $silent;

        return $this;
    }

    /**
     * Get silent
     *
     * @return boolean 
     */
    public function getSilent()
    {
        return $this->silent;
    }

    /**
     * Set sound
     *
     * @param string $sound
     * @return IosNotificationsNotification
     */
    public function setSound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Get sound
     *
     * @return string 
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return IosNotificationsNotification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lastSentAt
     *
     * @param \DateTime $lastSentAt
     * @return IosNotificationsNotification
     */
    public function setLastSentAt($lastSentAt)
    {
        $this->lastSentAt = $lastSentAt;

        return $this;
    }

    /**
     * Get lastSentAt
     *
     * @return \DateTime 
     */
    public function getLastSentAt()
    {
        return $this->lastSentAt;
    }

    /**
     * Set customPayload
     *
     * @param string $customPayload
     * @return IosNotificationsNotification
     */
    public function setCustomPayload($customPayload)
    {
        $this->customPayload = $customPayload;

        return $this;
    }

    /**
     * Get customPayload
     *
     * @return string 
     */
    public function getCustomPayload()
    {
        return $this->customPayload;
    }

    /**
     * Set locPayload
     *
     * @param string $locPayload
     * @return IosNotificationsNotification
     */
    public function setLocPayload($locPayload)
    {
        $this->locPayload = $locPayload;

        return $this;
    }

    /**
     * Get locPayload
     *
     * @return string 
     */
    public function getLocPayload()
    {
        return $this->locPayload;
    }

    /**
     * Set service
     *
     * @param \Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice $service
     * @return IosNotificationsNotification
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
