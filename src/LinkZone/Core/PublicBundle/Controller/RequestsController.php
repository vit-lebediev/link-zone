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

    public function apiSendOrderAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['senderPlatformId'],
                   $requestData['senderLink'],
                   $requestData['senderLinkText'],
                   $requestData['receiverPlatformId'])) {
            throw new BadRequestHttpException("Provided data is not sufficient");
        }

        if (!$senderPlatform = $this->_platformRepository->find($requestData['senderPlatformId']) 
                OR $this->_user !== $senderPlatform->getOwner()
                OR !$receiverPlatform = $this->_platformRepository->find($requestData['receiverPlatformId'])) {
            throw new BadRequestHttpException("There is no platform with id " + $requestData['senderPlatformId']);
        }

        // TODO: check that receiver platform is visible in search results, otherwice used doesn't want to get exchange orders

        $order = new PlatformRequest();

        $order->setSenderPlatform($senderPlatform);
        $order->setSenderUser($this->_user);
        $order->setSenderLink($requestData['senderLink']);
        $order->setSenderLinkText($requestData['senderLinkText']);

        $order->setReceiverPlatform($receiverPlatform);
        $order->setReceiverUser($receiverPlatform->getOwner());
        $order->setStatus(PlatformRequest::STATUS_EXCHANGE);
        $order->setCreated(new \DateTime());

        $errors = $this->_validator->validate($order);

        if (count($errors) > 0) {
            return new JsonResponse(array('errors' => $this->_parseValidationErrors($errors)), 400);
        }

        $this->_doctrineManager->persist($order);
        $this->_doctrineManager->flush();

        return new JsonResponse($this->_requestManager->toArray($order));
    }

    public function ajaxDialogReviewOrderAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $reviewRequestDialog = $this->createForm(new ReviewRequestType(), new PlatformRequest());

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

    public function apiListAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        if (!$status = $request->get("status")) {
            throw new BadRequestHttpException("You should provide status of the orders");
        }

        switch ($status) {
            case "exchange-sent":
                $orders = $this->_requestRepository->findAllSentForExchangeForUser($this->_user);
                break;
            case "exchange-received":
                $orders = $this->_requestRepository->findAllReceivedForExchangeForUser($this->_user);
                break;
            default:
                throw new BadRequestHttpException("This status is not supported");
        }

        $ordersArray = [];

        foreach ($orders as $order) {
            $ordersArray[] = $this->_requestManager->toArray($order);
        }

        return new JsonResponse($ordersArray);
    }

    public function apiOrderAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        if (!$order = $this->_requestRepository->find($orderId)) {
            return new JsonResponse(array(
                'message' => $this->_translator->trans("requests.errors.no_order", array(), "LZCorePublicBundle"),
            ), 404);
        }

        if ($request->getMethod() == "POST") {
            switch ($request->get('action')) {
                case "approve":
                    if ($this->_user != $order->getReceiverUser()) {
                        $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
                        return new JsonResponse(array(
                            'message' => $this->_translator->trans("requests.errors.no_order", array(), "LZCorePublicBundle"),
                        ), 404);
                    }

                    $requestData = json_decode($request->getContent(), true);

                    $order->setReceiverLink($requestData['receiverLink']);
                    $order->setReceiverLinkText($requestData['receiverLinkText']);
                    $order->setStatus(PlatformRequest::STATUS_IN_PROGRESS);

                    $errors = $this->_validator->validate($order);

                    if (count($errors) > 0) {
                        return new JsonResponse(array('errors' => $this->_parseValidationErrors($errors)), 400);
                    }

                    $this->_doctrineManager->persist($order);
                    $this->_doctrineManager->flush();

                    return new JsonResponse($this->_requestManager->toArray($order));
                case "deny":
                    if ($this->_user != $order->getReceiverUser()) {
                        $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $platformRequest->getId());
                        return new JsonResponse(array(
                            'message' => $this->_translator->trans("requests.errors.no_order", array(), "LZCorePublicBundle"),
                        ), 404);
                    }

                    $order->setStatus(PlatformRequest::STATUS_DENIED);

                    $this->_doctrineManager->persist($order);
                    $this->_doctrineManager->flush();

                    // TODO: Send a notification/message to the sender user about the rejection of the order

                    return new JsonResponse();
                default:
                    throw new BadRequestHttpException("Provided action is not supported");
            }
        } else {
            return new JsonResponse($this->_requestManager->toArray($order));
        }
    }

    public function apiDeleteOrderAction($orderId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        if (!$order = $this->_requestRepository->find($orderId)) {
            return new JsonResponse(array(
                'message' => $this->_translator->trans("requests.errors.no_order", array(), "LZCorePublicBundle"),
            ), 404);
        }

        if ($this->_user !== $order->getSenderPlatform()->getOwner()) {
            $this->_logger->warn("User with id " . $this->_user->getId() . " made an attempt to access part of the data of order with id " . $order->getId());
            return new JsonResponse(array(
                'message' => $this->_translator->trans("requests.errors.no_order", array(), "LZCorePublicBundle"),
            ), 404);
        }

        $this->_doctrineManager->remove($order);
        $this->_doctrineManager->flush();

        return new JsonResponse();
    }
}
