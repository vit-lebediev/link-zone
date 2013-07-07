<?php

namespace LinkZone\Core\PublicBundle\Entity;

use LinkZone\Core\PublicBundle\Entity\Dialog;
use LinkZone\Core\PublicBundle\Entity\Platform;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $sent;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Dialog
     */
    private $dialog;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $senderPlatform;

    /**
     * @var \LinkZone\Core\PublicBundle\Entity\Platform
     */
    private $receiverPlatform;


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
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sent
     *
     * @param \DateTime $sent
     * @return Message
     */
    public function setSent(\DateTime $sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set dialog
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Dialog $dialog
     * @return Message
     */
    public function setDialog(Dialog $dialog = null)
    {
        $this->dialog = $dialog;

        return $this;
    }

    /**
     * Get dialog
     *
     * @return \LinkZone\Core\PublicBundle\Entity\Dialog
     */
    public function getDialog()
    {
        return $this->dialog;
    }

    /**
     * Set senderPlatform
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $senderPlatform
     * @return Message
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
     * @return Message
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
