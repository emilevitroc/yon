<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IosNotificationsApnservice
 *
 * @ORM\Table(name="ios_notifications_apnservice", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name", "hostname"})})
 * @ORM\Entity
 */
class IosNotificationsApnservice
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
     * @var string
     *
     * @ORM\Column(name="certificate", type="text", nullable=false)
     */
    private $certificate;

    /**
     * @var string
     *
     * @ORM\Column(name="private_key", type="text", nullable=false)
     */
    private $privateKey;

    /**
     * @var string
     *
     * @ORM\Column(name="passphrase", type="string", length=110, nullable=true)
     */
    private $passphrase;



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
     * @return IosNotificationsApnservice
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
     * @return IosNotificationsApnservice
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
     * Set certificate
     *
     * @param string $certificate
     * @return IosNotificationsApnservice
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * Get certificate
     *
     * @return string 
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set privateKey
     *
     * @param string $privateKey
     * @return IosNotificationsApnservice
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string 
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set passphrase
     *
     * @param string $passphrase
     * @return IosNotificationsApnservice
     */
    public function setPassphrase($passphrase)
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    /**
     * Get passphrase
     *
     * @return string 
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }
}
