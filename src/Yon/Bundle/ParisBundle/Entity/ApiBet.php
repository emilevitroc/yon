<?php

namespace Yon\Bundle\ParisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiBet
 *
 * @ORM\Table(name="api_bet", uniqueConstraints={@ORM\UniqueConstraint(name="api_bet_user_id_608afe07aa942f84_uniq", columns={"user_id", "challenge_id"})}, indexes={@ORM\Index(name="api_bet_903d3026", columns={"challenge_id"}), @ORM\Index(name="api_bet_e8701ad4", columns={"user_id"}), @ORM\Index(name="creation", columns={"creation", "user_id"})})
 * @ORM\Entity
 */
class ApiBet
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
     * @ORM\Column(name="result", type="string", length=4, nullable=false)
     */
    private $result;

    /**
     * @var \ApiChallenge
     *
     * @ORM\ManyToOne(targetEntity="ApiChallenge", inversedBy="bets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var \AuthUser
     *
     * @ORM\ManyToOne(targetEntity="\Yon\Bundle\UserBundle\Entity\AuthUser")
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
     * @return ApiBet
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
     * Set result
     *
     * @param string $result
     * @return ApiBet
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set challenge
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge
     * @return ApiBet
     */
    public function setChallenge(\Yon\Bundle\ParisBundle\Entity\ApiChallenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \Yon\Bundle\ParisBundle\Entity\ApiChallenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiBet
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
