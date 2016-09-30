<?php

namespace Yon\Bundle\PrivilegeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiModule
 *
 * @ORM\Table(name="api_module")
 * @ORM\Entity
 */
class ApiModule
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
     * @var Yon\Bundle\PrivilegeBundle\Entity\ApiFeature
     *
     * @ORM\OneToMany(targetEntity="ApiFeature", mappedBy="apiModule")
     */
    private $features;



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
     * @return ApiModule
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
     * Add ad
     *
     * @param ApiFeature $features
     * @return ApiModule
     */
    public function addFeatures(ApiFeature $features) {
        $this->features[] = $features;

        return $this;
    }

    /**
     * Remove ad
     *
     * @param ApiFeature $features
     */
    public function removeFeatures(ApiFeature $features) {
        $this->features->removeElement($features);
    }

    /**
     * Get ad
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeatures() {
        return $this->features;
    }
}
