<?php

namespace LinkZone\Core\PublicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Taggable\Taggable;

use Doctrine\ORM\ORMInvalidArgumentException;

use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\Entity\PlatformTopic;
use LinkZone\Core\PublicBundle\Entity\Request;

use \JsonSerializable as JsonSerializable;

/**
 * Platform
 */
class Platform implements Taggable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $created;

        /**
     * @var boolean
     */
    private $hidden;

    /**
     * @var string
     */
    private $activationCode;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $owner;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\PlatformTopic
     */
    private $topic;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $tags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requestsSent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requestsReceived;

    const STATUS_NOT_CONFIRMED = "NOT_CONFIRMED";
    const STATUS_ACTIVE        = "ACTIVE";
    const STATUS_BLOCKED       = "BLOCKED";
    const STATUS_DELETED       = "DELETED";
    const STATUS_ON_MODERATION = "ON_MODERATION";
    const STATUS_DENIED        = "DENIED";

    public static $availableStatuses = [
        self::STATUS_NOT_CONFIRMED,
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
        self::STATUS_DELETED,
        self::STATUS_ON_MODERATION,
        self::STATUS_DENIED,
    ];

    const TAGGABLE_TYPE = "platform";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requestsSent     = new ArrayCollection();
        $this->requestsReceived = new ArrayCollection();
    }

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
     * Set url
     *
     * @param string $url
     * @return Platform
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Platform
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Platform
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$availableStatuses)) {
            throw new ORMInvalidArgumentException("You should provide valid status for a platform (pick from: " . implode(", ", self::$availableStatuses) . ")");
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
     * @return Platform
     */
    public function setCreated(\DateTime $created)
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
     * Set hidden
     *
     * @param boolean $hidden
     * @return Platform
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set activationCode
     *
     * @param string $activationCode
     * @return Platform
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Set owner
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $owner
     * @return Platform
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \LinkZone\Core\PublicBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set topic
     *
     * @param \LinkZone\Core\PublicBundle\Entity\PlatformTopic $topic
     * @return Platform
     */
    public function setTopic(PlatformTopic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \LinkZone\Core\PublicBundle\Entity\PlatformTopic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return self::TAGGABLE_TYPE;
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * Add sent request
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     * @return Platform
     */
    public function addRequestsSent(Request $request)
    {
        $this->requestsSent[] = $request;

        return $this;
    }

    /**
     * Remove sent request
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     */
    public function removeRequestsSent(Request $request)
    {
        $this->requestsSent->removeElement($request);
    }

    /**
     * Get sent requests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequestsSent()
    {
        return $this->requestsSent;
    }

    /**
     * Get active sent requests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActiveRequestsSent()
    {
        $activeRequests = new ArrayCollection();

        foreach ($this->getRequestsSent() as $request) {
            if ($request->isActive()) {
                $activeRequests[] = $request;
            }
        }

        return $activeRequests;
    }

    /**
     * Add received request
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     * @return Platform
     */
    public function addRequestsReceived(Request $request)
    {
        $this->requestsReceived[] = $request;

        return $this;
    }

    /**
     * Remove received request
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     */
    public function removeRequestsReceived(Request $request)
    {
        $this->requestsReceived->removeElement($request);
    }

    /**
     * Get received requests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequestsReceived()
    {
        return $this->requestsReceived;
    }

    /**
     * Get active received requests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActiveRequestsReceived()
    {
        $activeRequests = new ArrayCollection();

        foreach ($this->getRequestsReceived() as $request) {
            if ($request->isActive()) {
                $activeRequests[] = $request;
            }
        }

        return $activeRequests;
    }

    public function __toString()
    {
        return $this->getUrl();
    }
}
