<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Entity\Request as PlatformRequest;
use LinkZone\Core\PublicBundle\Form\Type\Request\SendRequestType;
use LinkZone\Core\PublicBundle\Form\Type\Request\ReviewRequestType;

class RequestsController extends BaseController
{
    private $_requestRepository;
    private $_requestManager;

    public function init()
    {
        parent::_init();

        $this->_requestRepository  = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Request");
        $this->_requestManager     = $this->get("link_zone.core.public.manager.request");
    }

    public function exchangeAction()
    {
        $ordersForExchangeReceived = $this->_requestRepository->findAllReceivedForExchangeForUser($this->_user);

        $ordersForExchangeSent = $this->_requestRepository->findAllSentForExchangeForUser($this->_user);

        return $this->render("LinkZoneCorePublicBundle:Requests:exchange.html.twig", array(
            'ordersReceived' => $ordersForExchangeReceived,
            'ordersSent'     => $ordersForExchangeSent,
        ));
    }

    public function inProgressAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Requests:inProgress.html.twig", array(
            'ordersReceived' => $this->_requestRepository->findAllReceivedInProgressForUser($this->_user),
            'ordersSent'     => $this->_requestRepository->findAllSentInProgressForUser($this->_user),
        ));
    }

    public function finishedAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Requests:finished.html.twig", array(
            'ordersReceived' => $this->_requestRepository->findAllReceivedFinishedForUser($this->_user),
            'ordersSent' => $this->_requestRepository->findAllSentFinishedForUser($this->_user),
        ));
    }

    /**
     * Ajax handlers
     */

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function ajaxSendOrderDialogAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $sendRequestDialog = $this->createForm(new SendRequestType(), new PlatformRequest(), array(
            'container' => $this->container,
            'user'      => $this->_user,
            'default_receiver_platform_id' => $request->get('platform_id'),
        ));

        return $this->render("LinkZoneCorePublicBundle:Requests:partials/send_request_dialog.html.twig", array(
            'sendRequestDialog' => $sendRequestDialog->createView(),
        ));
    }

    public function ajaxSendOrderAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = new PlatformRequest();

        $sendRequestDialog = $this->createForm(new SendRequestType(), $platformRequest, array(
            'container' => $this->container,
            'user'      => $this->_user,
        ));

        $sendRequestDialog->bind($request);

        if ($sendRequestDialog->isValid())
        {
            $platformRequest->setSenderUser($this->_user);

            $platformRequest->setReceiverUser($this->_platformRepository->find($request->get('send_request')['receiverPlatformId'])->getOwner());
            $platformRequest->setReceiverPlatform($this->_platformRepository->find($request->get('send_request')['receiverPlatformId']));
            $platformRequest->setStatus(PlatformRequest::STATUS_EXCHANGE);
            $platformRequest->setCreated(new \DateTime());

            $this->_doctrineManager->persist($platformRequest);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($sendRequestDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }

    public function ajaxDialogReviewOrderAction($orderId)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if ($this->_user != $platformRequest->getReceiverUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $request->getId());
            throw new BadRequestHttpException("You do not have rights to access this dialog");
        }

        $reviewRequestDialog = $this->createForm(new ReviewRequestType(), $platformRequest, array(
            'container' => $this->container,
            'senderLinkHTML' => $this->_requestManager->getSenderLinkHTML($platformRequest),
            'orderId' => $platformRequest->getId(),
        ));

        return $this->render("LinkZoneCorePublicBundle:Requests:partials/review_request_dialog.html.twig", array(
            'reviewRequestDialog' => $reviewRequestDialog->createView(),
        ));
    }

    public function ajaxApproveOrderAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if ($this->_user != $platformRequest->getReceiverUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
            throw new BadRequestHttpException("You do not have rights to access");
        }

        $reviewRequestDialog = $this->createForm(new ReviewRequestType(), $platformRequest, array(
            'container' => $this->container,
            'senderLinkHTML' => $this->_requestManager->getSenderLinkHTML($platformRequest),
        ));

        $reviewRequestDialog->bind($request);

        if ($reviewRequestDialog->isValid())
        {
            $platformRequest->setStatus(PlatformRequest::STATUS_IN_PROGRESS);

            $this->_doctrineManager->persist($platformRequest);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($reviewRequestDialog->getErrorsAsString());
            throw new BadRequestHttpException("Something went wrong");
        }
    }

    public function ajaxDenyOrderAction($orderId)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if ($this->_user != $platformRequest->getReceiverUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
            throw new BadRequestHttpException("You do not have rights to access");
        }

        $platformRequest->setStatus(PlatformRequest::STATUS_DENIED);

        $this->_doctrineManager->persist($platformRequest);
        $this->_doctrineManager->flush();

        return new JsonResponse();
    }

    public function ajaxReceiverLinkLocationAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if ($this->_user != $platformRequest->getReceiverUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
            throw new BadRequestHttpException("You do not have rights to access");
        }

        $platformRequest->setReceiverLinkLocation($request->get("linkLocation"));

        $this->_doctrineManager->persist($platformRequest);
        $this->_doctrineManager->flush();

        return new JsonResponse();
    }

    public function ajaxSenderLinkLocationAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if ($this->_user != $platformRequest->getSenderUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
            throw new BadRequestHttpException("You do not have rights to access");
        }

        $platformRequest->setSenderLinkLocation($request->get("linkLocation"));

        $this->_doctrineManager->persist($platformRequest);
        $this->_doctrineManager->flush();

        return new JsonResponse();
    }

    public function ajaxAcceptOrderAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $platformRequest = $this->_requestRepository->find($orderId);

        if (!$platformRequest) {
            throw new BadRequestHttpException("There is no order with id " . $orderId);
        }

        if ($this->_user != $platformRequest->getReceiverUser() AND $this->_user != $platformRequest->getSenderUser()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
            throw new BadRequestHttpException("You do not have rights to access");
        }

        $accept = ("false" == $request->get("accept")) ? false : true;

        if ($this->_user == $platformRequest->getReceiverUser()) {
            $platformRequest->setReceiverAccepted($accept);
        } else {
            $platformRequest->setSenderAccepted($accept);
        }

        if ($platformRequest->getSenderAccepted() && $platformRequest->getReceiverAccepted()) {
            $platformRequest->setStatus(PlatformRequest::STATUS_FINISHED);
            $platformRequest->setFinished(new \DateTime());

            // TODO: Charge users
        }

        $this->_doctrineManager->persist($platformRequest);
        $this->_doctrineManager->flush();

        return new JsonResponse();
    }
}
