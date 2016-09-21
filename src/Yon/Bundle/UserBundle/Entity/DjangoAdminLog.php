<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DjangoAdminLog
 *
 * @ORM\Table(name="django_admin_log", indexes={@ORM\Index(name="djang_content_type_id_697914295151027a_fk_django_content_type_id", columns={"content_type_id"}), @ORM\Index(name="django_admin_log_user_id_52fdd58701c5f563_fk_auth_user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class DjangoAdminLog
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
     * @ORM\Column(name="action_time", type="datetime", nullable=false)
     */
    private $actionTime;

    /**
     * @var string
     *
     * @ORM\Column(name="object_id", type="text", nullable=true)
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="object_repr", type="string", length=200, nullable=false)
     */
    private $objectRepr;

    /**
     * @var integer
     *
     * @ORM\Column(name="action_flag", type="smallint", nullable=false)
     */
    private $actionFlag;

    /**
     * @var string
     *
     * @ORM\Column(name="change_message", type="text", nullable=false)
     */
    private $changeMessage;

    /**
     * @var \DjangoContentType
     *
     * @ORM\ManyToOne(targetEntity="DjangoContentType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="content_type_id", referencedColumnName="id")
     * })
     */
    private $contentType;

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
     * Set actionTime
     *
     * @param \DateTime $actionTime
     * @return DjangoAdminLog
     */
    public function setActionTime($actionTime)
    {
        $this->actionTime = $actionTime;

        return $this;
    }

    /**
     * Get actionTime
     *
     * @return \DateTime 
     */
    public function getActionTime()
    {
        return $this->actionTime;
    }

    /**
     * Set objectId
     *
     * @param string $objectId
     * @return DjangoAdminLog
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return string 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectRepr
     *
     * @param string $objectRepr
     * @return DjangoAdminLog
     */
    public function setObjectRepr($objectRepr)
    {
        $this->objectRepr = $objectRepr;

        return $this;
    }

    /**
     * Get objectRepr
     *
     * @return string 
     */
    public function getObjectRepr()
    {
        return $this->objectRepr;
    }

    /**
     * Set actionFlag
     *
     * @param integer $actionFlag
     * @return DjangoAdminLog
     */
    public function setActionFlag($actionFlag)
    {
        $this->actionFlag = $actionFlag;

        return $this;
    }

    /**
     * Get actionFlag
     *
     * @return integer 
     */
    public function getActionFlag()
    {
        return $this->actionFlag;
    }

    /**
     * Set changeMessage
     *
     * @param string $changeMessage
     * @return DjangoAdminLog
     */
    public function setChangeMessage($changeMessage)
    {
        $this->changeMessage = $changeMessage;

        return $this;
    }

    /**
     * Get changeMessage
     *
     * @return string 
     */
    public function getChangeMessage()
    {
        return $this->changeMessage;
    }

    /**
     * Set contentType
     *
     * @param \Yon\Bundle\UserBundle\Entity\DjangoContentType $contentType
     * @return DjangoAdminLog
     */
    public function setContentType(\Yon\Bundle\UserBundle\Entity\DjangoContentType $contentType = null)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return \Yon\Bundle\UserBundle\Entity\DjangoContentType 
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return DjangoAdminLog
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
