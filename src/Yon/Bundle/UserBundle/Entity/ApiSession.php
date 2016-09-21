<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiSession
 *
 * @ORM\Table(name="api_session", indexes={@ORM\Index(name="api_session_9379346c", columns={"device_id"}), @ORM\Index(name="token", columns={"token"}), @ORM\Index(name="api_session_user_id_2fb59fcd7cf47607_fk_auth_user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiSession
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
     * @ORM\Column(name="token", type="string", length=100, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="invalidated", type="boolean", nullable=false)
     */
    private $invalidated;

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
     * Set creation
     *
     * @param \DateTime $creation
     * @return ApiSession
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
     * Set token
     *
     * @param string $token
     * @return ApiSession
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
     * Set invalidated
     *
     * @param boolean $invalidated
     * @return ApiSession
     */
    public function setInvalidated($invalidated)
    {
        $this->invalidated = $invalidated;

        return $this;
    }

    /**
     * Get invalidated
     *
     * @return boolean 
     */
    public function getInvalidated()
    {
        return $this->invalidated;
    }

    /**
     * Set device
     *
     * @param \Yon\Bundle\UserBundle\Entity\IosNotificationsDevice $device
     * @return ApiSession
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
     * @return ApiSession
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
