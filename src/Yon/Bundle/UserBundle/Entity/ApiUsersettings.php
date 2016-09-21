<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUsersettings
 *
 * @ORM\Table(name="api_usersettings", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id"})}, indexes={@ORM\Index(name="notification_daily_time", columns={"notification_daily_time"})})
 * @ORM\Entity
 */
class ApiUsersettings
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
     * @ORM\Column(name="notification_daily_time", type="time", nullable=true)
     */
    private $notificationDailyTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_contest_new_challenge", type="boolean", nullable=false)
     */
    private $notificationContestNewChallenge;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_contest_ended", type="boolean", nullable=false)
     */
    private $notificationContestEnded;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_contest_new_similar", type="boolean", nullable=false)
     */
    private $notificationContestNewSimilar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_friend_first_challenge", type="boolean", nullable=false)
     */
    private $notificationFriendFirstChallenge;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_badge_obtained", type="boolean", nullable=false)
     */
    private $notificationBadgeObtained;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notification_badge_almost_obtained", type="boolean", nullable=false)
     */
    private $notificationBadgeAlmostObtained;

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
     * Set notificationDailyTime
     *
     * @param \DateTime $notificationDailyTime
     * @return ApiUsersettings
     */
    public function setNotificationDailyTime($notificationDailyTime)
    {
        $this->notificationDailyTime = $notificationDailyTime;

        return $this;
    }

    /**
     * Get notificationDailyTime
     *
     * @return \DateTime 
     */
    public function getNotificationDailyTime()
    {
        return $this->notificationDailyTime;
    }

    /**
     * Set notificationContestNewChallenge
     *
     * @param boolean $notificationContestNewChallenge
     * @return ApiUsersettings
     */
    public function setNotificationContestNewChallenge($notificationContestNewChallenge)
    {
        $this->notificationContestNewChallenge = $notificationContestNewChallenge;

        return $this;
    }

    /**
     * Get notificationContestNewChallenge
     *
     * @return boolean 
     */
    public function getNotificationContestNewChallenge()
    {
        return $this->notificationContestNewChallenge;
    }

    /**
     * Set notificationContestEnded
     *
     * @param boolean $notificationContestEnded
     * @return ApiUsersettings
     */
    public function setNotificationContestEnded($notificationContestEnded)
    {
        $this->notificationContestEnded = $notificationContestEnded;

        return $this;
    }

    /**
     * Get notificationContestEnded
     *
     * @return boolean 
     */
    public function getNotificationContestEnded()
    {
        return $this->notificationContestEnded;
    }

    /**
     * Set notificationContestNewSimilar
     *
     * @param boolean $notificationContestNewSimilar
     * @return ApiUsersettings
     */
    public function setNotificationContestNewSimilar($notificationContestNewSimilar)
    {
        $this->notificationContestNewSimilar = $notificationContestNewSimilar;

        return $this;
    }

    /**
     * Get notificationContestNewSimilar
     *
     * @return boolean 
     */
    public function getNotificationContestNewSimilar()
    {
        return $this->notificationContestNewSimilar;
    }

    /**
     * Set notificationFriendFirstChallenge
     *
     * @param boolean $notificationFriendFirstChallenge
     * @return ApiUsersettings
     */
    public function setNotificationFriendFirstChallenge($notificationFriendFirstChallenge)
    {
        $this->notificationFriendFirstChallenge = $notificationFriendFirstChallenge;

        return $this;
    }

    /**
     * Get notificationFriendFirstChallenge
     *
     * @return boolean 
     */
    public function getNotificationFriendFirstChallenge()
    {
        return $this->notificationFriendFirstChallenge;
    }

    /**
     * Set notificationBadgeObtained
     *
     * @param boolean $notificationBadgeObtained
     * @return ApiUsersettings
     */
    public function setNotificationBadgeObtained($notificationBadgeObtained)
    {
        $this->notificationBadgeObtained = $notificationBadgeObtained;

        return $this;
    }

    /**
     * Get notificationBadgeObtained
     *
     * @return boolean 
     */
    public function getNotificationBadgeObtained()
    {
        return $this->notificationBadgeObtained;
    }

    /**
     * Set notificationBadgeAlmostObtained
     *
     * @param boolean $notificationBadgeAlmostObtained
     * @return ApiUsersettings
     */
    public function setNotificationBadgeAlmostObtained($notificationBadgeAlmostObtained)
    {
        $this->notificationBadgeAlmostObtained = $notificationBadgeAlmostObtained;

        return $this;
    }

    /**
     * Get notificationBadgeAlmostObtained
     *
     * @return boolean 
     */
    public function getNotificationBadgeAlmostObtained()
    {
        return $this->notificationBadgeAlmostObtained;
    }

    /**
     * Set user
     *
     * @param \Yon\Bundle\UserBundle\Entity\AuthUser $user
     * @return ApiUsersettings
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
