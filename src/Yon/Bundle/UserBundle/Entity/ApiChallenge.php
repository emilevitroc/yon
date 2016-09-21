<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiChallenge
 *
 * @ORM\Table(name="api_challenge", indexes={@ORM\Index(name="api_challenge_e4858d5c", columns={"hashtag_id"}), @ORM\Index(name="api_challenge_e8701ad4", columns={"user_id"}), @ORM\Index(name="result_published", columns={"result_published"}), @ORM\Index(name="cancelled", columns={"cancelled"}), @ORM\Index(name="end_date", columns={"end_date"}), @ORM\Index(name="result", columns={"result"}), @ORM\Index(name="bets_count", columns={"bets_count"}), @ORM\Index(name="status", columns={"status"}), @ORM\Index(name="popularity_score", columns={"popularity_score"})})
 * @ORM\Entity
 */
class ApiChallenge
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="result", type="string", length=4, nullable=true)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=false)
     */
    private $color;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cancelled", type="boolean", nullable=false)
     */
    private $cancelled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="result_published", type="boolean", nullable=false)
     */
    private $resultPublished;

    /**
     * @var integer
     *
     * @ORM\Column(name="prize", type="integer", nullable=true)
     */
    private $prize;

    /**
     * @var integer
     *
     * @ORM\Column(name="bet_price", type="integer", nullable=false)
     */
    private $betPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="alert_message", type="string", length=200, nullable=true)
     */
    private $alertMessage;

    /**
     * @var integer
     *
     * @ORM\Column(name="bets_count", type="integer", nullable=false)
     */
    private $betsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="comments_count", type="integer", nullable=false)
     */
    private $commentsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="winners_count", type="integer", nullable=false)
     */
    private $winnersCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity_score", type="integer", nullable=false)
     */
    private $popularityScore;

    /**
     * @var \ApiHashtag
     *
     * @ORM\ManyToOne(targetEntity="ApiHashtag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hashtag_id", referencedColumnName="id")
     * })
     */
    private $hashtag;

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
     * @return ApiChallenge
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
     * Set title
     *
     * @param string $title
     * @return ApiChallenge
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
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return ApiChallenge
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return ApiChallenge
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
     * Set color
     *
     * @param string $color
     * @return ApiChallenge
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set cancelled
     *
     * @param boolean $cancelled
     * @return ApiChallenge
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    /**
     * Get cancelled
     *
     * @return boolean 
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * Set resultPublished
     *
     * @param boolean $resultPublished
     * @return ApiChallenge
     */
    public function setResultPublished($resultPublished)
    {
        $this->resultPublished = $resultPublished;

        return $this;
    }

    /**
     * Get resultPublished
     *
     * @return boolean 
     */
    public function getResultPublished()
    {
        return $this->resultPublished;
    }

    /**
     * Set prize
     *
     * @param integer $prize
     * @return ApiChallenge
     */
    public function setPrize($prize)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return integer 
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * Set betPrice
     *
     * @param integer $betPrice
     * @return ApiChallenge
     */
    public function setBetPrice($betPrice)
    {
        $this->betPrice = $betPrice;

        return $this;
    }

    /**
     * Get betPrice
     *
     * @return integer 
     */
    public function getBetPrice()
    {
        return $this->betPrice;
    }

    /**
     * Set alertMessage
     *
     * @param string $alertMessage
     * @return ApiChallenge
     */
    public function setAlertMessage($alertMessage)
    {
        $this->alertMessage = $alertMessage;

        return $this;
    }

    /**
     * Get alertMessage
     *
     * @return string 
     */
    public function getAlertMessage()
    {
        return $this->alertMessage;
    }

    /**
     * Set betsCount
     *
     * @param integer $betsCount
     * @return ApiChallenge
     */
    public function setBetsCount($betsCount)
    {
        $this->betsCount = $betsCount;

        return $this;
    }

    /**
     * Get betsCount
     *
     * @return integer 
     */
    public function getBetsCount()
    {
        return $this->betsCount;
    }

    /**
     * Set commentsCount
     *
     * @param integer $commentsCount
     * @return ApiChallenge
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    /**
     * Get commentsCount
     *
     * @return integer 
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * Set winnersCount
     *
     * @param integer $winnersCount
     * @return ApiChallenge
     */
    public function setWinnersCount($winnersCount)
    {
        $this->winnersCount = $winnersCount;

        return $this;
    }

    /**
     * Get winnersCount
     *
     * @return integer 
     */
    public function getWinnersCount()
    {
        return $this->winnersCount;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ApiChallenge
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set popularityScore
     *
     * @param integer $popularityScore
     * @return ApiChallenge
     */
    public function setPopularityScore($popularityScore)
    {
        $this->popularityScore = $popularityScore;

        return $this;
    }

    /**
     * Get popularityScore
     *
     * @return integer 
     */
    public function getPopularityScore()
    {
        return $this->popularityScore;
    }

    /**
     * Set hashtag
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiHashtag $hashtag
     * @return ApiChallenge
     */
    public function setHashtag(\Yon\Bundle\UserBundle\Entity\ApiHashtag $hashtag = null)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiHashtag 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiChallenge
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
