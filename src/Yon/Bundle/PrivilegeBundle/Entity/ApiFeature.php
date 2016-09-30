<?php

namespace Yon\Bundle\PrivilegeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiFeature
 *
 * @ORM\Table(name="api_feature", indexes={@ORM\Index(name="api_module_id", columns={"api_module_id"})})
 * @ORM\Entity
 */
class ApiFeature
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_super_admin", type="boolean", nullable=false)
     */
    private $isSuperAdmin = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin_intern", type="boolean", nullable=false)
     */
    private $isAdminIntern = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin_extern", type="boolean", nullable=false)
     */
    private $isAdminExtern = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_partenaire_commercial", type="boolean", nullable=false)
     */
    private $isPartenaireCommercial = 0;

    /**
     * @var \ApiModule
     *
     * @ORM\ManyToOne(targetEntity="ApiModule", inversedBy="features")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="api_module_id", referencedColumnName="id")
     * })
     */
    private $apiModule;



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
     * Set title
     *
     * @param string $title
     * @return ApiFeature
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isSuperAdmin
     *
     * @param boolean $isSuperAdmin
     * @return ApiFeature
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    /**
     * Get isSuperAdmin
     *
     * @return boolean 
     */
    public function getIsSuperAdmin()
    {
        return $this->isSuperAdmin;
    }

    /**
     * Set isAdminIntern
     *
     * @param boolean $isAdminIntern
     * @return ApiFeature
     */
    public function setIsAdminIntern($isAdminIntern)
    {
        $this->isAdminIntern = $isAdminIntern;

        return $this;
    }

    /**
     * Get isAdminIntern
     *
     * @return boolean 
     */
    public function getIsAdminIntern()
    {
        return $this->isAdminIntern;
    }

    /**
     * Set isAdminExtern
     *
     * @param boolean $isAdminExtern
     * @return ApiFeature
     */
    public function setIsAdminExtern($isAdminExtern)
    {
        $this->isAdminExtern = $isAdminExtern;

        return $this;
    }

    /**
     * Get isAdminExtern
     *
     * @return boolean 
     */
    public function getIsAdminExtern()
    {
        return $this->isAdminExtern;
    }

    /**
     * Set isPartenaireCommercial
     *
     * @param boolean $isPartenaireCommercial
     * @return ApiFeature
     */
    public function setIsPartenaireCommercial($isPartenaireCommercial)
    {
        $this->isPartenaireCommercial = $isPartenaireCommercial;

        return $this;
    }

    /**
     * Get isPartenaireCommercial
     *
     * @return boolean 
     */
    public function getIsPartenaireCommercial()
    {
        return $this->isPartenaireCommercial;
    }

    /**
     * Set apiModule
     *
     * @param \Yon\Bundle\PrivilegeBundle\Entity\ApiModule $apiModule
     * @return ApiFeature
     */
    public function setApiModule(\Yon\Bundle\PrivilegeBundle\Entity\ApiModule $apiModule = null)
    {
        $this->apiModule = $apiModule;

        return $this;
    }

    /**
     * Get apiModule
     *
     * @return \Yon\Bundle\PrivilegeBundle\Entity\ApiModule 
     */
    public function getApiModule()
    {
        return $this->apiModule;
    }
}
