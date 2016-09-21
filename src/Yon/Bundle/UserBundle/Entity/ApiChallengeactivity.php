<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiChallengeactivity
 *
 * @ORM\Table(name="api_challengeactivity", uniqueConstraints={@ORM\UniqueConstraint(name="api_challengeactivity_user_id_2c31747cdf8a212b_uniq", columns={"user_id", "challenge_id"})}, indexes={@ORM\Index(name="challenge_id", columns={"challenge_id"}), @ORM\Index(name="user_id", columns={"user_id", "read"}), @ORM\Index(name="IDX_58E8ACEAA76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class ApiChallengeactivity
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
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="read", type="boolean", nullable=false)
     */
    private $read;

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
     * Set type
     *
     * @param string $type
     * @return ApiChallengeactivity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set read
     *
     * @param boolean $read
     * @return ApiChallengeactivity
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean 
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge
     * @return ApiChallengeactivity
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
     * @return ApiChallengeactivity
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
