<?php

namespace LinkZone\Core\PublicBundle\Entity;

use LinkZone\Core\PublicBundle\Entity\Message;
use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\Entity\Platform;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Dialog
 */
class Dialog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $senderUser;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\User
     */
    private $receiverUser;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $senderPlatform;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $receiverPlatform;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * Set created
     *
     * @param \DateTime $created
     * @return Dialog
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
     * Set senderUser
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $senderUser
     * @return Dialog
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
     * Set receiverUser
     *
     * @param \LinkZone\Core\PublicBundle\Entity\User $receiverUser
     * @return Dialog
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
     * Set senderPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $senderPlatform
     * @return Dialog
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
     * Set receiverPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $receiverPlatform
     * @return Dialog
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

    /**
     * Add messages
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Message $messages
     * @return Dialog
     */
    public function addMessage(Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Message $messages
     */
    public function removeMessage(Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
