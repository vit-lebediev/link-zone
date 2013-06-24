<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Entity\Request as PlatformRequest;
use LinkZone\Core\PublicBundle\Form\Type\SendRequestType;

class RequestsController extends BaseController
{
    private $_platformRepository;
    private $_userRepository;
    private $_doctrineManager;
    private $_logger;
    private $_user;

    public function init()
    {
        $this->_platformRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Platform");
        $this->_userRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        $this->_doctrineManager = $this->getDoctrine()->getManager();
        $this->_logger          = $this->get("logger");
        $this->_user            = $this->get("security.context")->getToken()->getUser();

    }

    public function exchangeAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Requests:exchange.html.twig");
    }

    public function inProgressAction()
    {
        return $this->render("LinkZoneCorePublicBundle:Requests:inProgress.html.twig");
    }

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

            // set receiver user
            $platformRequest->setReceiverUser($this->_platformRepository->find($request->get('send_request')['receiverPlatformId'])->getOwner());
            // set receiver platform
            $platformRequest->setReceiverPlatform($this->_platformRepository->find($request->get('send_request')['receiverPlatformId']));
            // set status
            $platformRequest->setStatus(PlatformRequest::STATUS_EXCHANGE);
            // set created
            $platformRequest->setCreated(new \DateTime());

            $this->_doctrineManager->persist($platformRequest);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($sendRequestDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }
}
