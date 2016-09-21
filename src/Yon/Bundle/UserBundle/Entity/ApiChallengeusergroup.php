<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiChallengeusergroup
 *
 * @ORM\Table(name="api_challengeusergroup", uniqueConstraints={@ORM\UniqueConstraint(name="api_challengeusergroup_challenge_id_9b2f5e895ae3d5f_uniq", columns={"challenge_id", "user_group_id"})}, indexes={@ORM\Index(name="user_group_id", columns={"user_group_id"}), @ORM\Index(name="IDX_81F1573E98A21AC6", columns={"challenge_id"})})
 * @ORM\Entity
 */
class ApiChallengeusergroup
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
     * @var \ApiChallenge
     *
     * @ORM\ManyToOne(targetEntity="ApiChallenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var \ApiUsergroup
     *
     * @ORM\ManyToOne(targetEntity="ApiUsergroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_group_id", referencedColumnName="id")
     * })
     */
    private $userGroup;



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
     * @return ApiChallengeusergroup
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
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge
     * @return ApiChallengeusergroup
     */
    public function setChallenge(\Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiChallenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set userGroup
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiUsergroup $userGroup
     * @return ApiChallengeusergroup
     */
    public function setUserGroup(\Yon\Bundle\UserBundle\Entity\ApiUsergroup $userGroup = null)
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * Get userGroup
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiUsergroup 
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }
}
