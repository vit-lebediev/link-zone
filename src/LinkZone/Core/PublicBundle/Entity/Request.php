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
    private $recieverLink;

    /**
     * @var string
     */
    private $recieverLinkText;

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
    private $recieverUser;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $recieverPlatform;

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
     * Set recieverLink
     *
     * @param string $recieverLink
     * @return Request
     */
    public function setRecieverLink($recieverLink)
    {
        $this->recieverLink = $recieverLink;

        return $this;
    }

    /**
     * Get recieverLink
     *
     * @return string
     */
    public function getRecieverLink()
    {
        return $this->recieverLink;
    }

    /**
     * Set recieverLinkText
     *
     * @param string $recieverLinkText
     * @return Request
     */
    public function setRecieverLinkText($recieverLinkText)
    {
        $this->recieverLinkText = $recieverLinkText;

        return $this;
    }

    /**
     * Get recieverLinkText
     *
     * @return string
     */
    public function getRecieverLinkText()
    {
        return $this->recieverLinkText;
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
     * Set recieverUser
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $recieverUser
     * @return Request
     */
    public function setRecieverUser(User $recieverUser = null)
    {
        $this->recieverUser = $recieverUser;

        return $this;
    }

    /**
     * Get recieverUser
     *
     * @return \LinkZone\Core\PublicBundle\Entity\User
     */
    public function getRecieverUser()
    {
        return $this->recieverUser;
    }

    /**
     * Set recieverPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $recieverPlatform
     * @return Request
     */
    public function setRecieverPlatform(Platform $recieverPlatform = null)
    {
        $this->recieverPlatform = $recieverPlatform;

        return $this;
    }

    /**
     * Get recieverPlatform
     *
     * @return \LinkZone\Core\PublicBundle\Entity\Platform
     */
    public function getRecieverPlatform()
    {
        return $this->recieverPlatform;
    }
}
