<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IosNotificationsFeedbackservice
 *
 * @ORM\Table(name="ios_notifications_feedbackservice", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name", "hostname"})}, indexes={@ORM\Index(name="ddfe8ea753af68c76f241b796107b7c9", columns={"apn_service_id"})})
 * @ORM\Entity
 */
class IosNotificationsFeedbackservice
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=255, nullable=false)
     */
    private $hostname;

    /**
     * @var \IosNotificationsApnservice
     *
     * @ORM\ManyToOne(targetEntity="IosNotificationsApnservice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="apn_service_id", referencedColumnName="id")
     * })
     */
    private $apnService;



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
     * Set name
     *
     * @param string $name
     * @return IosNotificationsFeedbackservice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return IosNotificationsFeedbackservice
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set apnService
     *
     * @param \Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice $apnService
     * @return IosNotificationsFeedbackservice
     */
    public function setApnService(\Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice $apnService = null)
    {
        $this->apnService = $apnService;

        return $this;
    }

    /**
     * Get apnService
     *
     * @return \Yon\Bundle\UserBundle\Entity\IosNotificationsApnservice 
     */
    public function getApnService()
    {
        return $this->apnService;
    }
}
