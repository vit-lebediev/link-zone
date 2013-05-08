<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Entity\Platform;
use LinkZone\Core\PublicBundle\Form\Type\PlatformType;

class PlatformsController extends Controller
{
    private $_platformRepository;
    private $_doctrineManager;
    private $_translator;
    private $_logger;
    private $_user;

    public function init()
    {
        $this->_platformRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Platform");
        $this->_doctrineManager    = $this->getDoctrine()->getManager();
        $this->_translator         = $this->get('translator');
        $this->_logger             = $this->get('logger');
        $this->_user               = $this->get('security.context')->getToken()->getUser();
    }

    public function indexAction()
    {
        $platformDialog = $this->createForm(new PlatformType(), new Platform(), array(
            'container' => $this->container,
        ));

        return $this->render("LinkZoneCorePublicBundle:Platforms:index.html.twig", array(
            'platformDialog' => $platformDialog->createView(),
            'platforms'      => $this->_user->getPlatforms(),
        ));
    }

    public function ajaxPlatformDialogAction(Request $request)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        if ($platformId = $request->get("platform_id")) {
            $platform = $this->_platformRepository->find($platformId);
        } else {
            $platform = new Platform();
        }

        $platformDialog = $this->createForm(new PlatformType(), $platform, array(
            'container' => $this->container,
        ));

        return $this->render("LinkZoneCorePublicBundle:Platforms:partials/platform_dialog.html.twig", array(
            'platformDialog' => $platformDialog->createView(),
        ));
    }

    /**
     * Add a new Platform
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws BadRequestHttpException
     */
    public function ajaxAddPlatformAction(Request $request)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        $platform = new Platform();

        $platformDialog = $this->createForm(new PlatformType(), $platform, array(
            'container' => $this->container,
        ));

        $platformDialog->bind($request);

        if ($platformDialog->isValid())
        {
            $platform->setStatus(Platform::STATUS_ON_MODERATION)
                     ->setCreated(new \DateTime())
                     ->setOwner($this->_user);

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($platformDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }

    public function ajaxEditPlatformAction($platformId, Request $request)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        if (!$platform = $this->_platformRepository->find($platformId)) {
            throw new BadRequestHttpException("There is no platform with id " . $platformId);
        }

        $platformDialog = $this->createForm(new PlatformType(), $platform, array(
            'container' => $this->container,
        ));

        $platformDialog->bind($request);

        if ($platformDialog->isValid())
        {
            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            return new JsonResponse();
        } else {
            $this->_logger->err($platformDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }
}
