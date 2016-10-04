<?php

namespace Yon\Bundle\ParisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUserhashtag
 *
 * @ORM\Table(name="api_userhashtag", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="hashtag_id", columns={"hashtag_id", "rank"}), @ORM\Index(name="IDX_ACA35C51FB34EF56", columns={"hashtag_id"})})
 * @ORM\Entity
 */
class ApiUserhashtag
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
     * @ORM\Column(name="balance", type="integer", nullable=false)
     */
    private $balance;

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
     * @return ApiUserhashtag
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
     * @return ApiUserhashtag
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
     * Set balance
     *
     * @param integer $balance
     * @return ApiUserhashtag
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set hashtag
     *
     * @param \Yon\Bundle\ParisBundle\Entity\ApiHashtag $hashtag
     * @return ApiUserhashtag
     */
    public function setHashtag(\Yon\Bundle\ParisBundle\Entity\ApiHashtag $hashtag = null)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return \Yon\Bundle\ParisBundle\Entity\ApiHashtag 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUserhashtag
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
