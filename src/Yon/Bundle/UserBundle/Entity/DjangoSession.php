<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DjangoSession
 *
 * @ORM\Table(name="django_session", indexes={@ORM\Index(name="django_session_de54fa62", columns={"expire_date"})})
 * @ORM\Entity
 */
class DjangoSession
{
    /**
     * @var string
     *
     * @ORM\Column(name="session_key", type="string", length=40, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessionKey;

    /**
     * @var string
     *
     * @ORM\Column(name="session_data", type="text", nullable=false)
     */
    private $sessionData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_date", type="datetime", nullable=false)
     */
    private $expireDate;



    /**
     * Get sessionKey
     *
     * @return string 
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * Set sessionData
     *
     * @param string $sessionData
     * @return DjangoSession
     */
    public function setSessionData($sessionData)
    {
        $this->sessionData = $sessionData;

        return $this;
    }

    /**
     * Get sessionData
     *
     * @return string 
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     * @return DjangoSession
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime 
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }
}
