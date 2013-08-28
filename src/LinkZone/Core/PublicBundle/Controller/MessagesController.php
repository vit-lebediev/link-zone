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
        $this->_dialogManager = $this->get("link_zone.core.public.manager.dialog");
    }

    public function indexAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Messages:index.html.twig", array(
            'dialogues' => $this->_dialogRepository->findAllForUser($this->_user),
        ));
    }

    /**
     * Ajax handlers
     */

    public function apiSendMessageAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $requestData = json_decode($request->getContent(), true);

        // check that senderPlatformId and receiverPlatformId are correct
        if ((!$senderPlatformId = $requestData['senderPlatformId']) OR
                (!$receiverPlatformId = $requestData['receiverPlatformId']))
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
        $dialog = $this->_dialogRepository->findForPlatforms($messageSenderPlatform, $messageReceiverPlatform);

        // if there is no one, create new
        if (!$dialog) {
            $dialog = new Dialog();
            $dialog->setSenderPlatform($messageSenderPlatform);
            $dialog->setReceiverPlatform($messageReceiverPlatform);
            $dialog->setSenderUser($messageSenderPlatform->getOwner());
            $dialog->setReceiverUser($messageReceiverPlatform->getOwner());
            $dialog->setCreated(new \DateTime());
        }

        $message = new Message();

        $message->setMessage($requestData['message']);

        $errors = $this->_validator->validate($message);

        if (count($errors) > 0) {
            $this->_logger->err($sendMessageDialog->getErrorsAsString());
            return new JsonResponse(array('errors' => $this->_parseValidationErrors($errors)), 400);
        }

        // save message
        $message->setSent(new \DateTime())
                ->setDialog($dialog)
                ->setSenderPlatform($messageSenderPlatform)
                ->setReceiverPlatform($messageReceiverPlatform);

        $dialog->setUpdated(new \DateTime());

        $this->_doctrineManager->persist($message);
        $this->_doctrineManager->flush();

        return new JsonResponse();
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

    public function apiDialogListAction()
    {
        $this->_verifyIsXmlHttpRequest();

        return new JsonResponse($this->_dialogManager->getDialogs());
    }

    public function apiDialogAction($dialogId)
    {
        $this->_verifyIsXmlHttpRequest();

        return new JsonResponse($this->_dialogManager->getDialog($dialogId));
    }
}
