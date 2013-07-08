<?php

namespace LinkZone\Core\PublicBundle\Controller;

use LinkZone\Core\PublicBundle\Entity\Message;
use LinkZone\Core\PublicBundle\Form\Type\MessageType;
use LinkZone\Core\PublicBundle\Entity\Dialog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessagesController extends BaseController
{
    private $_dialogRepository;

    public function init()
    {
        parent::_init();
        $this->_dialogRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Dialog");
    }

    public function indexAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Messages:index.html.twig", array(
            'dialogues' => $this->_dialogRepository->findAllForUser($this->_user),
        ));
    }

    public function dialogAction($dialogId)
    {
        if (!$dialog = $this->_dialogRepository->findForUser($dialogId, $this->_user)) {
            throw new NotFoundHttpException("There is no dialog with id " . $dialogId);
        }

        return $this->render("LinkZoneCorePublicBundle:Messages:dialog.html.twig", array(
            'dialog' => $dialog,
        ));
    }

    /**
     * Ajax handlers
     */

    public function ajaxSendMessageAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        // check that senderPlatformId and receiverPlatformId are correct
        if ((!$senderPlatformId = $request->get("message")['senderPlatformId']) OR
                (!$receiverPlatformId = $request->get("message")['receiverPlatformId']))
        {
            // TODO: log this error
            throw new BadRequestHttpException("senderPlatformId AND receiverPlatformId must be specified");
        }

        $messageSenderPlatform = $this->_platformRepository->find($senderPlatformId);
        $messageReceiverPlatform = $this->_platformRepository->find($receiverPlatformId);

        // check that senderPlatformId belong to current user
        if ($messageSenderPlatform->getOwner() != $this->_user) {
            // TODO: log this error
            throw new BadRequestHttpException("Current user do not posses the platfrom: are you trying to send message not under the name of your platform ?");
        }

        // check that receiverPlatformId belong to one of users with which current user has active requests
        // This platform is message sender, and could not have any requests sent (!)
        foreach($messageSenderPlatform->getActiveRequestsSent() as $order) {
            if ($order->getReceiverPlatform() == $messageReceiverPlatform) {
                goto receiverPlatformValid;
            }
        }

        foreach($messageSenderPlatform->getActiveRequestsReceived() as $order) {
            if ($order->getSenderPlatform() == $messageReceiverPlatform) {
                goto receiverPlatformValid;
            }
        }

        throw new BadRequestHttpException("Receiver platform is not valid");

        receiverPlatformValid:
        // find dialog, corresponding to these two platforms
            // if there is one, add message to it
            // if there is no one, create new
        $dialog = $this->_dialogRepository->findForPlatforms($messageSenderPlatform, $messageReceiverPlatform);

        if (!$dialog) {
            $dialog = new Dialog();
            $dialog->setSenderPlatform($messageSenderPlatform);
            $dialog->setReceiverPlatform($messageReceiverPlatform);
            $dialog->setSenderUser($messageSenderPlatform->getOwner());
            $dialog->setReceiverUser($messageReceiverPlatform->getOwner());
            $dialog->setCreated(new \DateTime());
        }

        $message = new Message();

        $sendMessageDialog = $this->createForm(new MessageType(), $message, array(
            'senderPlatformId' => $messageSenderPlatform->getId(),
            'receiverPlatformId' => $messageReceiverPlatform->getId(),
        ));

        $sendMessageDialog->bind($request);

        if ($sendMessageDialog->isValid())
        {
            // save message
            $message->setSent(new \DateTime())
                    ->setDialog($dialog)
                    ->setSenderPlatform($messageSenderPlatform)
                    ->setReceiverPlatform($messageReceiverPlatform);

            $dialog->setUpdated(new \DateTime());

            $this->_doctrineManager->persist($message);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($sendMessageDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided message data is not valid. Are you trying to CSRF us ?");
        }
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @throws BadRequestHttpException
     */
    public function ajaxDialogSendMessageAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        // check that request have senderPlatformId and receiverPlatformId params
        if ((!$senderPlatformId = $request->get("senderPlatformId")) OR
                (!$receiverPlatformId = $request->get("receiverPlatformId")))
        {
            throw new BadRequestHttpException("senderPlatformId AND receiverPlatformId must be specified");
        }

        // TODO: check that senderPlatform belong to current user, and receiverPlatformId - to one of users whith which current user has active orders

        $sendMessageDialog = $this->createForm(new MessageType(), new Message(), array(
            'senderPlatformId' => $senderPlatformId,
            'receiverPlatformId' => $receiverPlatformId,
        ));

        return $this->render("LinkZoneCorePublicBundle:Messages:partials/send_message_dialog.html.twig", array(
            'sendMessageDialog' => $sendMessageDialog->createView(),
        ));
    }
}
