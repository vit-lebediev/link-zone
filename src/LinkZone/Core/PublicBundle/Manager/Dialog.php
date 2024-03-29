<?php

namespace LinkZone\Core\PublicBundle\Manager;

use LinkZone\Core\PublicBundle\Entity\Dialog as DialogEntity;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Dialog extends ContainerAware
{
    private $_dialogRepo;
    private $_user;

    const DEFAULT_NUMBER_OF_MESSAGES_TO_DISPLAY = 10;

    public function init()
    {
        $this->_dialogRepo = $this->container->get('doctrine')->getRepository("LinkZoneCorePublicBundle:Dialog");
        $this->_user       = $this->container->get('security.context')->getToken()->getUser();
    }

    /**
     * Return dialogs as array of values, which could be exposed to front-end application
     * @return array An array of dialogs
     */
    public function getDialogs()
    {
        $dialogs = $this->_dialogRepo->findAllForUser($this->_user);

        $dialogsArray = [];
        foreach ($dialogs as $dialog) {
            $dialogsArray[] = $this->toArray($dialog);
        }

        return $dialogsArray;
    }

    /**
     *
     * @param type $dialogId
     * @return type
     * @throws NotFoundHttpException
     */
    public function getDialog($dialogId)
    {
        if (!$dialog = $this->_dialogRepo->find($dialogId)) {
            throw new NotFoundHttpException("There is no dialog with id " . $dialogId);
        }

        // TODO: check that user has access to this dialog

        return $this->prepareDialogArray($dialog);
    }

    public function getMessages($dialogId, $offset = 0, $number = self::DEFAULT_NUMBER_OF_MESSAGES_TO_DISPLAY)
    {
        if (!$dialog = $this->_dialogRepo->find($dialogId)) {
            throw new NotFoundHttpException("There is no dialog with id " . $dialogId);
        }

        $reversedMessagesArray = array_reverse($dialog->getMessages()->toArray());
        $dialogMessages = array_slice($reversedMessagesArray, $offset, $number);
        $dialogMessages = array_reverse($dialogMessages);

        $messages = [];
        foreach ($dialogMessages as $message) {
            $messages[] = array(
                'message' => $message->getMessage(),
                'isIncoming' => ($message->getSenderPlatform()->getOwner() === $this->_user) ? false : true,
                'isNotIncoming' => ($message->getSenderPlatform()->getOwner() === $this->_user) ? true : false,
            );
        }

        return $messages;
    }

    /**
     * Convert dialog to array. Short version, for dialog list.
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Dialog $dialog
     * @return type
     */
    public function toArray(DialogEntity $dialog) {
        $lastMessageInDialog = $dialog->getMessages()->last();

        $lastMessageText = $lastMessageInDialog->getMessage();

        $preparedLastMessage = mb_strlen($lastMessageText) > 100 ? mb_substr($lastMessageText, 0, 100) . "..." : $lastMessageText;

        return array(
            'id' => $dialog->getId(),
            'lastMessage' => $preparedLastMessage,
            'isLastMessageSentByMe' => ($lastMessageInDialog->getSenderPlatform()->getOwner() === $this->_user) ? true : false,
            'isLastMessageSentNotByMe' => ($lastMessageInDialog->getSenderPlatform()->getOwner() === $this->_user) ? false : true,
            'lastMessageSenderPlatformUrl' => $lastMessageInDialog->getSenderPlatform()->getUrl(),
            'lastMessageReceiverPlatformUrl' => $lastMessageInDialog->getReceiverPlatform()->getUrl(),
        );
    }

    /**
     * Convert dialog to array with messages and all required information for displaying in dialog window
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Dialog $dialog
     * @return array                                             An array representation of a dialog
     */
    public function prepareDialogArray(DialogEntity $dialog) {
        $messages = [];

        $sliceOffset = $dialog->getMessages()->count() - $this->container->getParameter("default_message_num_in_dialog");
        $dialogMessages = array_slice($dialog->getMessages()->toArray(), $sliceOffset, $this->container->getParameter("default_message_num_in_dialog"));

        foreach ($dialogMessages as $message) {
            $messages[] = array(
                'message' => $message->getMessage(),
                'isIncoming' => ($message->getSenderPlatform()->getOwner() === $this->_user) ? false : true,
                'isNotIncoming' => ($message->getSenderPlatform()->getOwner() === $this->_user) ? true : false,
            );
        }

        return array(
            'id' => $dialog->getId(),
            'messages' => $messages,
            'myPlatform' => array(
                'id'  => ($dialog->getSenderUser() === $this->_user) ? $dialog->getSenderPlatform()->getId() : $dialog->getReceiverPlatform()->getId(),
                'url' => ($dialog->getSenderUser() === $this->_user) ? $dialog->getSenderPlatform()->getUrl() : $dialog->getReceiverPlatform()->getUrl(),
            ),
            'companionPlatform' => array(
                'id'  => ($dialog->getSenderUser() === $this->_user) ? $dialog->getReceiverPlatform()->getId() : $dialog->getSenderPlatform()->getId(),
                'url' => ($dialog->getSenderUser() === $this->_user) ? $dialog->getReceiverPlatform()->getUrl() : $dialog->getSenderPlatform()->getUrl(),
            ),
        );
    }
}
