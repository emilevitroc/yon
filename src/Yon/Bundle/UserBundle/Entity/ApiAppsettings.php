<?php

namespace Yon\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiAppsettings
 *
 * @ORM\Table(name="api_appsettings")
 * @ORM\Entity
 */
class ApiAppsettings
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
     * @ORM\Column(name="power_user_min_pending_bets", type="integer", nullable=false)
     */
    private $powerUserMinPendingBets;

    /**
     * @var integer
     *
     * @ORM\Column(name="power_user_min_successful_bets", type="integer", nullable=false)
     */
    private $powerUserMinSuccessfulBets;

    /**
     * @var integer
     *
     * @ORM\Column(name="power_user_min_followers", type="integer", nullable=false)
     */
    private $powerUserMinFollowers;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_users_min_count", type="integer", nullable=true)
     */
    private $featuredUsersMinCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_users_newcomers_count", type="integer", nullable=false)
     */
    private $featuredUsersNewcomersCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_users_powerusers_count", type="integer", nullable=false)
     */
    private $featuredUsersPowerusersCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_users_reactivates_count", type="integer", nullable=false)
     */
    private $featuredUsersReactivatesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_users_stars_count", type="integer", nullable=false)
     */
    private $featuredUsersStarsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_followers_level_2", type="integer", nullable=false)
     */
    private $badgeFollowersLevel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_followers_level_3", type="integer", nullable=false)
     */
    private $badgeFollowersLevel3;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_played_level_2", type="integer", nullable=false)
     */
    private $badgePlayedLevel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_played_level_3", type="integer", nullable=false)
     */
    private $badgePlayedLevel3;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_success_level_2", type="integer", nullable=false)
     */
    private $badgeSuccessLevel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_success_level_3", type="integer", nullable=false)
     */
    private $badgeSuccessLevel3;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_won_level_2", type="integer", nullable=false)
     */
    private $badgeWonLevel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_won_level_3", type="integer", nullable=false)
     */
    private $badgeWonLevel3;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_followers_level_1", type="integer", nullable=false)
     */
    private $badgeFollowersLevel1;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_played_level_1", type="integer", nullable=false)
     */
    private $badgePlayedLevel1;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_success_level_1", type="integer", nullable=false)
     */
    private $badgeSuccessLevel1;

    /**
     * @var integer
     *
     * @ORM\Column(name="badge_won_level_1", type="integer", nullable=false)
     */
    private $badgeWonLevel1;



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
     * @return ApiAppsettings
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
     * Set powerUserMinPendingBets
     *
     * @param integer $powerUserMinPendingBets
     * @return ApiAppsettings
     */
    public function setPowerUserMinPendingBets($powerUserMinPendingBets)
    {
        $this->powerUserMinPendingBets = $powerUserMinPendingBets;

        return $this;
    }

    /**
     * Get powerUserMinPendingBets
     *
     * @return integer 
     */
    public function getPowerUserMinPendingBets()
    {
        return $this->powerUserMinPendingBets;
    }

    /**
     * Set powerUserMinSuccessfulBets
     *
     * @param integer $powerUserMinSuccessfulBets
     * @return ApiAppsettings
     */
    public function setPowerUserMinSuccessfulBets($powerUserMinSuccessfulBets)
    {
        $this->powerUserMinSuccessfulBets = $powerUserMinSuccessfulBets;

        return $this;
    }

    /**
     * Get powerUserMinSuccessfulBets
     *
     * @return integer 
     */
    public function getPowerUserMinSuccessfulBets()
    {
        return $this->powerUserMinSuccessfulBets;
    }

    /**
     * Set powerUserMinFollowers
     *
     * @param integer $powerUserMinFollowers
     * @return ApiAppsettings
     */
    public function setPowerUserMinFollowers($powerUserMinFollowers)
    {
        $this->powerUserMinFollowers = $powerUserMinFollowers;

        return $this;
    }

    /**
     * Get powerUserMinFollowers
     *
     * @return integer 
     */
    public function getPowerUserMinFollowers()
    {
        return $this->powerUserMinFollowers;
    }

    /**
     * Set featuredUsersMinCount
     *
     * @param integer $featuredUsersMinCount
     * @return ApiAppsettings
     */
    public function setFeaturedUsersMinCount($featuredUsersMinCount)
    {
        $this->featuredUsersMinCount = $featuredUsersMinCount;

        return $this;
    }

    /**
     * Get featuredUsersMinCount
     *
     * @return integer 
     */
    public function getFeaturedUsersMinCount()
    {
        return $this->featuredUsersMinCount;
    }

    /**
     * Set featuredUsersNewcomersCount
     *
     * @param integer $featuredUsersNewcomersCount
     * @return ApiAppsettings
     */
    public function setFeaturedUsersNewcomersCount($featuredUsersNewcomersCount)
    {
        $this->featuredUsersNewcomersCount = $featuredUsersNewcomersCount;

        return $this;
    }

    /**
     * Get featuredUsersNewcomersCount
     *
     * @return integer 
     */
    public function getFeaturedUsersNewcomersCount()
    {
        return $this->featuredUsersNewcomersCount;
    }

    /**
     * Set featuredUsersPowerusersCount
     *
     * @param integer $featuredUsersPowerusersCount
     * @return ApiAppsettings
     */
    public function setFeaturedUsersPowerusersCount($featuredUsersPowerusersCount)
    {
        $this->featuredUsersPowerusersCount = $featuredUsersPowerusersCount;

        return $this;
    }

    /**
     * Get featuredUsersPowerusersCount
     *
     * @return integer 
     */
    public function getFeaturedUsersPowerusersCount()
    {
        return $this->featuredUsersPowerusersCount;
    }

    /**
     * Set featuredUsersReactivatesCount
     *
     * @param integer $featuredUsersReactivatesCount
     * @return ApiAppsettings
     */
    public function setFeaturedUsersReactivatesCount($featuredUsersReactivatesCount)
    {
        $this->featuredUsersReactivatesCount = $featuredUsersReactivatesCount;

        return $this;
    }

    /**
     * Get featuredUsersReactivatesCount
     *
     * @return integer 
     */
    public function getFeaturedUsersReactivatesCount()
    {
        return $this->featuredUsersReactivatesCount;
    }

    /**
     * Set featuredUsersStarsCount
     *
     * @param integer $featuredUsersStarsCount
     * @return ApiAppsettings
     */
    public function setFeaturedUsersStarsCount($featuredUsersStarsCount)
    {
        $this->featuredUsersStarsCount = $featuredUsersStarsCount;

        return $this;
    }

    /**
     * Get featuredUsersStarsCount
     *
     * @return integer 
     */
    public function getFeaturedUsersStarsCount()
    {
        return $this->featuredUsersStarsCount;
    }

    /**
     * Set badgeFollowersLevel2
     *
     * @param integer $badgeFollowersLevel2
     * @return ApiAppsettings
     */
    public function setBadgeFollowersLevel2($badgeFollowersLevel2)
    {
        $this->badgeFollowersLevel2 = $badgeFollowersLevel2;

        return $this;
    }

    /**
     * Get badgeFollowersLevel2
     *
     * @return integer 
     */
    public function getBadgeFollowersLevel2()
    {
        return $this->badgeFollowersLevel2;
    }

    /**
     * Set badgeFollowersLevel3
     *
     * @param integer $badgeFollowersLevel3
     * @return ApiAppsettings
     */
    public function setBadgeFollowersLevel3($badgeFollowersLevel3)
    {
        $this->badgeFollowersLevel3 = $badgeFollowersLevel3;

        return $this;
    }

    /**
     * Get badgeFollowersLevel3
     *
     * @return integer 
     */
    public function getBadgeFollowersLevel3()
    {
        return $this->badgeFollowersLevel3;
    }

    /**
     * Set badgePlayedLevel2
     *
     * @param integer $badgePlayedLevel2
     * @return ApiAppsettings
     */
    public function setBadgePlayedLevel2($badgePlayedLevel2)
    {
        $this->badgePlayedLevel2 = $badgePlayedLevel2;

        return $this;
    }

    /**
     * Get badgePlayedLevel2
     *
     * @return integer 
     */
    public function getBadgePlayedLevel2()
    {
        return $this->badgePlayedLevel2;
    }

    /**
     * Set badgePlayedLevel3
     *
     * @param integer $badgePlayedLevel3
     * @return ApiAppsettings
     */
    public function setBadgePlayedLevel3($badgePlayedLevel3)
    {
        $this->badgePlayedLevel3 = $badgePlayedLevel3;

        return $this;
    }

    /**
     * Get badgePlayedLevel3
     *
     * @return integer 
     */
    public function getBadgePlayedLevel3()
    {
        return $this->badgePlayedLevel3;
    }

    /**
     * Set badgeSuccessLevel2
     *
     * @param integer $badgeSuccessLevel2
     * @return ApiAppsettings
     */
    public function setBadgeSuccessLevel2($badgeSuccessLevel2)
    {
        $this->badgeSuccessLevel2 = $badgeSuccessLevel2;

        return $this;
    }

    /**
     * Get badgeSuccessLevel2
     *
     * @return integer 
     */
    public function getBadgeSuccessLevel2()
    {
        return $this->badgeSuccessLevel2;
    }

    /**
     * Set badgeSuccessLevel3
     *
     * @param integer $badgeSuccessLevel3
     * @return ApiAppsettings
     */
    public function setBadgeSuccessLevel3($badgeSuccessLevel3)
    {
        $this->badgeSuccessLevel3 = $badgeSuccessLevel3;

        return $this;
    }

    /**
     * Get badgeSuccessLevel3
     *
     * @return integer 
     */
    public function getBadgeSuccessLevel3()
    {
        return $this->badgeSuccessLevel3;
    }

    /**
     * Set badgeWonLevel2
     *
     * @param integer $badgeWonLevel2
     * @return ApiAppsettings
     */
    public function setBadgeWonLevel2($badgeWonLevel2)
    {
        $this->badgeWonLevel2 = $badgeWonLevel2;

        return $this;
    }

    /**
     * Get badgeWonLevel2
     *
     * @return integer 
     */
    public function getBadgeWonLevel2()
    {
        return $this->badgeWonLevel2;
    }

    /**
     * Set badgeWonLevel3
     *
     * @param integer $badgeWonLevel3
     * @return ApiAppsettings
     */
    public function setBadgeWonLevel3($badgeWonLevel3)
    {
        $this->badgeWonLevel3 = $badgeWonLevel3;

        return $this;
    }

    /**
     * Get badgeWonLevel3
     *
     * @return integer 
     */
    public function getBadgeWonLevel3()
    {
        return $this->badgeWonLevel3;
    }

    /**
     * Set badgeFollowersLevel1
     *
     * @param integer $badgeFollowersLevel1
     * @return ApiAppsettings
     */
    public function setBadgeFollowersLevel1($badgeFollowersLevel1)
    {
        $this->badgeFollowersLevel1 = $badgeFollowersLevel1;

        return $this;
    }

    /**
     * Get badgeFollowersLevel1
     *
     * @return integer 
     */
    public function getBadgeFollowersLevel1()
    {
        return $this->badgeFollowersLevel1;
    }

    /**
     * Set badgePlayedLevel1
     *
     * @param integer $badgePlayedLevel1
     * @return ApiAppsettings
     */
    public function setBadgePlayedLevel1($badgePlayedLevel1)
    {
        $this->badgePlayedLevel1 = $badgePlayedLevel1;

        return $this;
    }

    /**
     * Get badgePlayedLevel1
     *
     * @return integer 
     */
    public function getBadgePlayedLevel1()
    {
        return $this->badgePlayedLevel1;
    }

    /**
     * Set badgeSuccessLevel1
     *
     * @param integer $badgeSuccessLevel1
     * @return ApiAppsettings
     */
    public function setBadgeSuccessLevel1($badgeSuccessLevel1)
    {
        $this->badgeSuccessLevel1 = $badgeSuccessLevel1;

        return $this;
    }

    /**
     * Get badgeSuccessLevel1
     *
     * @return integer 
     */
    public function getBadgeSuccessLevel1()
    {
        return $this->badgeSuccessLevel1;
    }

    /**
     * Set badgeWonLevel1
     *
     * @param integer $badgeWonLevel1
     * @return ApiAppsettings
     */
    public function setBadgeWonLevel1($badgeWonLevel1)
    {
        $this->badgeWonLevel1 = $badgeWonLevel1;

        return $this;
    }

    /**
     * Get badgeWonLevel1
     *
     * @return integer 
     */
    public function getBadgeWonLevel1()
    {
        return $this->badgeWonLevel1;
    }
}
