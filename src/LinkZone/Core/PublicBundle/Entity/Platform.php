<?php

namespace LinkZone\Core\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\ORMInvalidArgumentException;

use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\Entity\PlatformTopic;

/**
 * Platform
 */
class Platform
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
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $owner;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\PlatformTopic
     */
    private $topic;

    const STATUS_ACTIVE        = "ACTIVE";
    const STATUS_BLOCKED       = "BLOCKED";
    const STATUS_DELETED       = "DELETED";
    const STATUS_ON_MODERATION = "ON_MODERATION";
    const STATUS_DENIED        = "DENIED";

    public static $availableStatuses = [
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
        self::STATUS_DELETED,
        self::STATUS_ON_MODERATION,
        self::STATUS_DENIED,
    ];

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
}
