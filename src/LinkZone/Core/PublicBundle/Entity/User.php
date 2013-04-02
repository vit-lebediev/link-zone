<?php

namespace LinkZone\Core\PublicBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
}
