<?php

namespace LinkZone\Core\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogStatusChangesEntry
 */
class LogStatusChangesEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $fromStatus;

    /**
     * @var string
     */
    private $toStatus;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $whoChanged;

    /**
     * @var string
     */
    private $reason;


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
     * Set userId
     *
     * @param integer $userId
     * @return LogStatusChangesEntry
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set fromStatus
     *
     * @param string $fromStatus
     * @return LogStatusChangesEntry
     */
    public function setFromStatus($fromStatus)
    {
        $this->fromStatus = $fromStatus;

        return $this;
    }

    /**
     * Get fromStatus
     *
     * @return string
     */
    public function getFromStatus()
    {
        return $this->fromStatus;
    }

    /**
     * Set toStatus
     *
     * @param string $toStatus
     * @return LogStatusChangesEntry
     */
    public function setToStatus($toStatus)
    {
        $this->toStatus = $toStatus;

        return $this;
    }

    /**
     * Get toStatus
     *
     * @return string
     */
    public function getToStatus()
    {
        return $this->toStatus;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return LogStatusChangesEntry
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set whoChanged
     *
     * @param string $whoChanged
     * @return LogStatusChangesEntry
     */
    public function setWhoChanged($whoChanged)
    {
        $this->whoChanged = $whoChanged;

        return $this;
    }

    /**
     * Get whoChanged
     *
     * @return string
     */
    public function getWhoChanged()
    {
        return $this->whoChanged;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return LogStatusChangesEntry
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
