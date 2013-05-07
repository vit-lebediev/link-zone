<?php

namespace LinkZone\Core\PublicBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\ORMInvalidArgumentException;

use LinkZone\Core\PublicBundle\Entity\Platform;

/**
 * Users
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var float
     */
    private $ballance;

    /**
     * @var float
     */
    private $bonus;

    /**
     * @var string
     */
    private $status;

    /**
     * Account number in Ya.Dengy service
     *
     * @var string
     */
    private $billingYaDengy;

    /**
     * @var string
     */
    private $billingWMR;

    /**
     * @var string
     */
    private $billingWMZ;

    /**
     * @var \DateTime
     */
    private $registrationDate;

    /**
     * @var string
     */
    private $referralValue;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $referrer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $referrals;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $platforms;

    const STATUS_ACTIVE          = "ACTIVE";
    const STATUS_BLOCKED         = "BLOCKED";
    const STATUS_DELETED         = "DELETED";
    const STATUS_ACCOUNT_BLOCKED = "FUNDS_BLOCKED";
    const STATUS_PASSIVE         = "PASSIVE";

    public static $availableStatuses = array(
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
        self::STATUS_DELETED,
        self::STATUS_ACCOUNT_BLOCKED,
        self::STATUS_PASSIVE,
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->platforms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->referrals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set ballance
     *
     * @param float $ballance
     * @return Users
     */
    public function setBallance($ballance)
    {
        $this->ballance = $ballance;

        return $this;
    }

    /**
     * Get ballance
     *
     * @return float
     */
    public function getBallance()
    {
        return $this->ballance;
    }

    /**
     * Set bonus
     *
     * @param float $bonus
     * @return Users
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * Get bonus
     *
     * @return float
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Users
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$availableStatuses)) {
            throw new ORMInvalidArgumentException("You should provide valid status for a user (pick from: " . implode(", ", self::$availableStatuses) . ")");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set billingYaDengy
     *
     * @param string $billingYaDengy
     * @return Users
     */
    public function setBillingYaDengy($billingYaDengy)
    {
        $this->billingYaDengy = $billingYaDengy;

        return $this;
    }

    /**
     * Get billingYaDengy
     *
     * @return string
     */
    public function getBillingYaDengy()
    {
        return $this->billingYaDengy;
    }

    /**
     * Set billingWMR
     *
     * @param string $billingWMR
     * @return Users
     */
    public function setBillingWMR($billingWMR)
    {
        $this->billingWMR = $billingWMR;

        return $this;
    }

    /**
     * Get billingWMR
     *
     * @return string
     */
    public function getBillingWMR()
    {
        return $this->billingWMR;
    }

    /**
     * Set billingWMZ
     *
     * @param string $billingWMZ
     * @return Users
     */
    public function setBillingWMZ($billingWMZ)
    {
        $this->billingWMZ = $billingWMZ;

        return $this;
    }

    /**
     * Get billingWMZ
     *
     * @return string
     */
    public function getBillingWMZ()
    {
        return $this->billingWMZ;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return Users
     */
    public function setRegistrationDate(\DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set referralValue
     *
     * @param string $referralValue
     * @return User
     */
    public function setReferralValue($referralValue)
    {
        $this->referralValue = $referralValue;

        return $this;
    }

    /**
     * Get referralValue
     *
     * @return string
     */
    public function getReferralValue()
    {
        return $this->referralValue;
    }

    /**
     * Set referrer
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $referrer
     * @return User
     */
    public function setReferrer(User $referrer = null)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return \LinkZone\Core\PublicBundle\Entity\User
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Add referral
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $referral
     * @return User
     */
    public function addReferral(User $referral)
    {
        $this->referrals[] = $referral;

        return $this;
    }

    /**
     * Remove referral
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $referral
     */
    public function removeReferral(User $referral)
    {
        $this->referrals->removeElement($referral);
    }

    /**
     * Get referrals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferrals()
    {
        return $this->referrals;
    }

    /**
     * Add platforms
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @return User
     */
    public function addPlatform(Platform $platform)
    {
        $this->platforms[] = $platform;

        return $this;
    }

    /**
     * Remove platforms
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     */
    public function removePlatform(Platform $platform)
    {
        $this->platforms->removeElement($platforms);
    }

    /**
     * Get platforms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }
}
