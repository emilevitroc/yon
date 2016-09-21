<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthPermission
 *
 * @ORM\Table(name="auth_permission", uniqueConstraints={@ORM\UniqueConstraint(name="content_type_id", columns={"content_type_id", "codename"})}, indexes={@ORM\Index(name="IDX_FE4C9F1E1A445520", columns={"content_type_id"})})
 * @ORM\Entity
 */
class AuthPermission
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
     * @ORM\Column(name="codename", type="string", length=100, nullable=false)
     */
    private $codename;

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
     * @return AuthPermission
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
     * Set codename
     *
     * @param string $codename
     * @return AuthPermission
     */
    public function setCodename($codename)
    {
        $this->codename = $codename;

        return $this;
    }

    /**
     * Get codename
     *
     * @return string 
     */
    public function getCodename()
    {
        return $this->codename;
    }

    /**
     * Set contentType
     *
     * @param \Yon\Bundle\UserBundle\Entity\DjangoContentType $contentType
     * @return AuthPermission
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
}
