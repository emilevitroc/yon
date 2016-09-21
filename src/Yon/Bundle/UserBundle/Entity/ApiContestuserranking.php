<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiContestuserranking
 *
 * @ORM\Table(name="api_contestuserranking", uniqueConstraints={@ORM\UniqueConstraint(name="api_contestuserranking_contest_id_5fdcedf0a43b10a0_uniq", columns={"contest_id", "user_id"})}, indexes={@ORM\Index(name="api_contestuserranking_user_id_769d288d85062325_fk_auth_user_id", columns={"user_id"}), @ORM\Index(name="IDX_6DEBC8E91CD0F0DE", columns={"contest_id"})})
 * @ORM\Entity
 */
class ApiContestuserranking
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
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_won_trophy", type="boolean", nullable=false)
     */
    private $hasWonTrophy;

    /**
     * @var integer
     *
     * @ORM\Column(name="participants_in_followings", type="integer", nullable=false)
     */
    private $participantsInFollowings;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_rank", type="integer", nullable=false)
     */
    private $previousRank;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_rank_in_followings", type="integer", nullable=false)
     */
    private $previousRankInFollowings;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_in_followings", type="integer", nullable=false)
     */
    private $rankInFollowings;

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
     * @return ApiContestuserranking
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
     * Set rank
     *
     * @param integer $rank
     * @return ApiContestuserranking
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return ApiContestuserranking
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set hasWonTrophy
     *
     * @param boolean $hasWonTrophy
     * @return ApiContestuserranking
     */
    public function setHasWonTrophy($hasWonTrophy)
    {
        $this->hasWonTrophy = $hasWonTrophy;

        return $this;
    }

    /**
     * Get hasWonTrophy
     *
     * @return boolean 
     */
    public function getHasWonTrophy()
    {
        return $this->hasWonTrophy;
    }

    /**
     * Set participantsInFollowings
     *
     * @param integer $participantsInFollowings
     * @return ApiContestuserranking
     */
    public function setParticipantsInFollowings($participantsInFollowings)
    {
        $this->participantsInFollowings = $participantsInFollowings;

        return $this;
    }

    /**
     * Get participantsInFollowings
     *
     * @return integer 
     */
    public function getParticipantsInFollowings()
    {
        return $this->participantsInFollowings;
    }

    /**
     * Set previousRank
     *
     * @param integer $previousRank
     * @return ApiContestuserranking
     */
    public function setPreviousRank($previousRank)
    {
        $this->previousRank = $previousRank;

        return $this;
    }

    /**
     * Get previousRank
     *
     * @return integer 
     */
    public function getPreviousRank()
    {
        return $this->previousRank;
    }

    /**
     * Set previousRankInFollowings
     *
     * @param integer $previousRankInFollowings
     * @return ApiContestuserranking
     */
    public function setPreviousRankInFollowings($previousRankInFollowings)
    {
        $this->previousRankInFollowings = $previousRankInFollowings;

        return $this;
    }

    /**
     * Get previousRankInFollowings
     *
     * @return integer 
     */
    public function getPreviousRankInFollowings()
    {
        return $this->previousRankInFollowings;
    }

    /**
     * Set rankInFollowings
     *
     * @param integer $rankInFollowings
     * @return ApiContestuserranking
     */
    public function setRankInFollowings($rankInFollowings)
    {
        $this->rankInFollowings = $rankInFollowings;

        return $this;
    }

    /**
     * Get rankInFollowings
     *
     * @return integer 
     */
    public function getRankInFollowings()
    {
        return $this->rankInFollowings;
    }

    /**
     * Set contest
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiContest $contest
     * @return ApiContestuserranking
     */
    public function setContest(\Yon\Bundle\UserBundle\Entity\ApiContest $contest = null)
    {
        $this->contest = $contest;

        return $this;
    }

    /**
     * Get contest
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiContest 
     */
    public function getContest()
    {
        return $this->contest;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiContestuserranking
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
