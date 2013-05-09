<?php

namespace LinkZone\Core\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogPlatformStatusChangesEntry
 */
class LogPlatformStatusChangesEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $platformId;

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
     * Set platformId
     *
     * @param integer $platformId
     * @return LogPlatformStatusChangesEntry
     */
    public function setPlatformId($platformId)
    {
        $this->platformId = $platformId;

        return $this;
    }

    /**
     * Get platformId
     *
     * @return integer
     */
    public function getPlatformId()
    {
        return $this->platformId;
    }

    /**
     * Set fromStatus
     *
     * @param string $fromStatus
     * @return LogPlatformStatusChangesEntry
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
     * @return LogPlatformStatusChangesEntry
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
     * @return LogPlatformStatusChangesEntry
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
     * @return LogPlatformStatusChangesEntry
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
     * @return LogPlatformStatusChangesEntry
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
