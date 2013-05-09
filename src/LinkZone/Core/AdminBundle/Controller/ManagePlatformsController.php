<?php

namespace LinkZone\Core\AdminBundle\Controller;

// import core Symfony components
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// import exceptions
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

// import project components
use LinkZone\Core\PublicBundle\Entity\Platform;
use LinkZone\Core\AdminBundle\Entity\LogPlatformStatusChangesEntry;

class ManagePlatformsController extends Controller
{
    private $_platformRepository;
    private $_doctrineManager;
    private $_translator;
    private $_logger;

    public function init()
    {
        $this->_platformRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Platform");
        $this->_doctrineManager = $this->getDoctrine()->getManager();
        $this->_translator      = $this->get('translator');
        $this->_logger          = $this->get('logger');
    }

    public function listAction()
    {
        return $this->render("LinkZoneCoreAdminBundle:ManagePlatforms:list.html.twig", array(
            'platforms' => $this->_platformRepository->findBy(
                array(),
                array('created' => "DESC")
            ),
        ));
    }

    public function specificAction($platformId)
    {
        if (!$platform = $this->_platformRepository->find($platformId)) {
            throw new BadRequestHttpException("There is no platform with ID " . $userId);
        }

        $statusDropDown = $this->createFormBuilder($platform, array(
            'csrf_protection' => false,
        ))->add("status", "choice", array(
            'choices' => array(
                Platform::STATUS_ACTIVE        => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_ACTIVE,        array(), "LZCoreAdminBundle"),
                Platform::STATUS_BLOCKED       => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_BLOCKED,       array(), "LZCoreAdminBundle"),
                Platform::STATUS_DELETED       => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_DELETED,       array(), "LZCoreAdminBundle"),
                Platform::STATUS_ON_MODERATION => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_ON_MODERATION, array(), "LZCoreAdminBundle"),
                Platform::STATUS_DENIED        => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_DENIED,        array(), "LZCoreAdminBundle"),
            ),
        ))->getForm();

        return $this->render("LinkZoneCoreAdminBundle:ManagePlatforms:specific.html.twig", array(
            'platform' => $platform,
            'statusDropDown' => $statusDropDown->createView(),
        ));
    }

    public function ajaxSetStatusAction($platformId, Request $request)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        if (!$platform = $this->_platformRepository->find($platformId)) {
            throw new BadRequestHttpException("There is no platform with id " + $platformId);
        }

        if ($platform->getStatus() == $request->get("form[status]")) {
            throw new BadRequestHttpException("You should specify different status");
        }

        $prevStatus = $platform->getStatus();
        $reasonMessage = $this->getRequest()->get("reason");
        if (strlen($reasonMessage) == 0) {
            throw new BadRequestHttpException("You must specify reason for status change");
        }

        $statusDropDown = $this->createFormBuilder($platform, array(
            'csrf_protection' => false,
        ))->add("status", "choice", array(
            'choices' => array(
                Platform::STATUS_ACTIVE        => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_ACTIVE,        array(), "LZCoreAdminBundle"),
                Platform::STATUS_BLOCKED       => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_BLOCKED,       array(), "LZCoreAdminBundle"),
                Platform::STATUS_DELETED       => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_DELETED,       array(), "LZCoreAdminBundle"),
                Platform::STATUS_ON_MODERATION => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_ON_MODERATION, array(), "LZCoreAdminBundle"),
                Platform::STATUS_DENIED        => $this->_translator->trans("platform_management.statuses." . Platform::STATUS_DENIED,        array(), "LZCoreAdminBundle"),
            ),
        ))->getForm();

        $statusDropDown->bind($request);

        if ($statusDropDown->isValid()) {
            $this->_doctrineManager->persist($platform);

            $logPlatformStatusEntry = new LogPlatformStatusChangesEntry();
            $logPlatformStatusEntry->setPlatformId($platformId);
            $logPlatformStatusEntry->setFromStatus($prevStatus);
            $logPlatformStatusEntry->setToStatus($platform->getStatus());
            $logPlatformStatusEntry->setDate(new \DateTime());
            $logPlatformStatusEntry->setWhoChanged($this->get('security.context')->getToken()->getUser()->getUsername());
            $logPlatformStatusEntry->setReason($reasonMessage);

            $this->_doctrineManager->persist($logPlatformStatusEntry);

            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($statusDropDown->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform status is not valid");
        }
    }
}
