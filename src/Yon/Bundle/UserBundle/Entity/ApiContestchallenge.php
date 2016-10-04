<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiContestchallenge
 *
 * @ORM\Table(name="api_contestchallenge", uniqueConstraints={@ORM\UniqueConstraint(name="api_contestchallenge_contest_id_129f9f8720885f35_uniq", columns={"contest_id", "challenge_id"})}, indexes={@ORM\Index(name="challenge_id", columns={"challenge_id"}), @ORM\Index(name="IDX_8C820A461CD0F0DE", columns={"contest_id"})})
 * @ORM\Entity
 */
class ApiContestchallenge
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
     * @var \ApiContest
     *
     * @ORM\ManyToOne(targetEntity="ApiContest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contest_id", referencedColumnName="id")
     * })
     */
    private $contest;



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
     * @return ApiContestchallenge
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
     * @return ApiContestchallenge
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
     * Set contest
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiContest $contest
     * @return ApiContestchallenge
     */
    public function setContest(\Yon\Bundle\ParisBundle\Entity\ApiContest $contest = null)
    {
        $this->contest = $contest;

        return $this;
    }

    /**
     * Get contest
     *
     * @return \Yon\Bundle\ParisBundle\Entity\ApiContest 
     */
    public function getContest()
    {
        return $this->contest;
    }
}
