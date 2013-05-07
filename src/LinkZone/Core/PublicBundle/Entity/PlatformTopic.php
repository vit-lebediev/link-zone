<?php

namespace LinkZone\Core\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use LinkZone\Core\PublicBundle\Entity\Platform;

/**
 * PlatformTopic
 */
class PlatformTopic
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $transKey;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $platforms;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->platforms = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return PlatformTopic
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
     * Set transKey
     *
     * @param string $transKey
     * @return PlatformTopic
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;

        return $this;
    }

    /**
     * Get transKey
     *
     * @return string
     */
    public function getTransKey()
    {
        return $this->transKey;
    }

    /**
     * Add platforms
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platforms
     * @return PlatformTopic
     */
    public function addPlatform(Platform $platforms)
    {
        $this->platforms[] = $platforms;

        return $this;
    }

    /**
     * Remove platforms
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platforms
     */
    public function removePlatform(Platform $platforms)
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

    public function __toString() {
        return $this->getDescription();
    }
}
