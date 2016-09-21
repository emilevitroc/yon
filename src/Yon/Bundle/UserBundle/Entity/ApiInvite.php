<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiInvite
 *
 * @ORM\Table(name="api_invite", uniqueConstraints={@ORM\UniqueConstraint(name="api_invite_user_id_41a21672b5f9625_uniq", columns={"user_id", "challenge_id"})}, indexes={@ORM\Index(name="api_invite_903d3026", columns={"challenge_id"}), @ORM\Index(name="api_invite_e8701ad4", columns={"user_id"}), @ORM\Index(name="api_invite_36fc3d93", columns={"invited_by_id"})})
 * @ORM\Entity
 */
class ApiInvite
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
     * @var \ApiChallenge
     *
     * @ORM\ManyToOne(targetEntity="ApiChallenge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

    /**
     * @var \ApiUserprofile
     *
     * @ORM\ManyToOne(targetEntity="ApiUserprofile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invited_by_id", referencedColumnName="id")
     * })
     */
    private $invitedBy;

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
     * Set challenge
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiChallenge $challenge
     * @return ApiInvite
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
     * Set invitedBy
     *
     * @param \Yon\Bundle\UserBundle\Entity\ApiUserprofile $invitedBy
     * @return ApiInvite
     */
    public function setInvitedBy(\Yon\Bundle\UserBundle\Entity\ApiUserprofile $invitedBy = null)
    {
        $this->invitedBy = $invitedBy;

        return $this;
    }

    /**
     * Get invitedBy
     *
     * @return \Yon\Bundle\UserBundle\Entity\ApiUserprofile 
     */
    public function getInvitedBy()
    {
        return $this->invitedBy;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiInvite
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
