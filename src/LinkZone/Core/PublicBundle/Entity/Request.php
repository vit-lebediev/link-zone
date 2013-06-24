<?php

namespace LinkZone\Core\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\ORMInvalidArgumentException;

use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\Entity\Platform;

/**
 * Request
 */
class Request
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $senderLink;

    /**
     * @var string
     */
    private $senderLinkText;

    /**
     * @var string
     */
    private $receiverLink;

    /**
     * @var string
     */
    private $receiverLinkText;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $finished;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $senderUser;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $senderPlatform;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $receiverUser;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $receiverPlatform;

    const STATUS_EXCHANGE    = "EXCHANGE";
    const STATUS_IN_PROGRESS = "IN_PROGRESS";
    const STATUS_FINISHED    = "FINISHED";

    public static $availableStatuses = array(
        self::STATUS_EXCHANGE,
        self::STATUS_IN_PROGRESS,
        self::STATUS_FINISHED,
    );

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
     * Set senderLink
     *
     * @param string $senderLink
     * @return Request
     */
    public function setSenderLink($senderLink)
    {
        $this->senderLink = $senderLink;

        return $this;
    }

    /**
     * Get senderLink
     *
     * @return string
     */
    public function getSenderLink()
    {
        return $this->senderLink;
    }

    /**
     * Set senderLinkText
     *
     * @param string $senderLinkText
     * @return Request
     */
    public function setSenderLinkText($senderLinkText)
    {
        $this->senderLinkText = $senderLinkText;

        return $this;
    }

    /**
     * Get senderLinkText
     *
     * @return string
     */
    public function getSenderLinkText()
    {
        return $this->senderLinkText;
    }

    /**
     * Set receiverLink
     *
     * @param string $receiverLink
     * @return Request
     */
    public function setReceiverLink($receiverLink)
    {
        $this->receiverLink = $receiverLink;

        return $this;
    }

    /**
     * Get receiverLink
     *
     * @return string
     */
    public function getReceiverLink()
    {
        return $this->receiverLink;
    }

    /**
     * Set receiverLinkText
     *
     * @param string $receiverLinkText
     * @return Request
     */
    public function setReceiverLinkText($receiverLinkText)
    {
        $this->receiverLinkText = $receiverLinkText;

        return $this;
    }

    /**
     * Get receiverLinkText
     *
     * @return string
     */
    public function getReceiverLinkText()
    {
        return $this->receiverLinkText;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Request
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
     * Set created
     *
     * @param \DateTime $created
     * @return Request
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set finished
     *
     * @param \DateTime $finished
     * @return Request
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return \DateTime
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set senderUser
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $senderUser
     * @return Request
     */
    public function setSenderUser(User $senderUser = null)
    {
        $this->senderUser = $senderUser;

        return $this;
    }

    /**
     * Get senderUser
     *
     * @return \LinkZone\Core\PublicBundle\Entity\User
     */
    public function getSenderUser()
    {
        return $this->senderUser;
    }

    /**
     * Set senderPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $senderPlatform
     * @return Request
     */
    public function setSenderPlatform(Platform $senderPlatform = null)
    {
        $this->senderPlatform = $senderPlatform;

        return $this;
    }

    /**
     * Get senderPlatform
     *
     * @return \LinkZone\Core\PublicBundle\Entity\Platform
     */
    public function getSenderPlatform()
    {
        return $this->senderPlatform;
    }

    /**
     * Set receiverUser
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $receiverUser
     * @return Request
     */
    public function setReceiverUser(User $receiverUser = null)
    {
        $this->receiverUser = $receiverUser;

        return $this;
    }

    /**
     * Get receiverUser
     *
     * @return \LinkZone\Core\PublicBundle\Entity\User
     */
    public function getReceiverUser()
    {
        return $this->receiverUser;
    }

    /**
     * Set receiverPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $receiverPlatform
     * @return Request
     */
    public function setReceiverPlatform(Platform $receiverPlatform = null)
    {
        $this->receiverPlatform = $receiverPlatform;

        return $this;
    }

    /**
     * Get receiverPlatform
     *
     * @return \LinkZone\Core\PublicBundle\Entity\Platform
     */
    public function getReceiverPlatform()
    {
        return $this->receiverPlatform;
    }
}
