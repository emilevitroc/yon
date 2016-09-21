<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiReport
 *
 * @ORM\Table(name="api_report", indexes={@ORM\Index(name="api_report_69b97d17", columns={"comment_id"}), @ORM\Index(name="challenge_id", columns={"challenge_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiReport
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
     * @ORM\Column(name="uuid", type="string", length=16, nullable=false)
     */
    private $uuid;

    /**
     * @var \ApiChallengecomment
     *
     * @ORM\ManyToOne(targetEntity="ApiChallengecomment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
     * })
     */
    private $comment;

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
     * @return ApiReport
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
     * Set uuid
     *
     * @param string $uuid
     * @return ApiReport
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set comment
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallengecomment $comment
     * @return ApiReport
     */
    public function setComment(\Yon\Bundle\UserBundle\Entity\ApiChallengecomment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiChallengecomment 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge
     * @return ApiReport
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
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiReport
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
