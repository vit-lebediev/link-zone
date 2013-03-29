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
     * @var string
     */
    private $status;

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
