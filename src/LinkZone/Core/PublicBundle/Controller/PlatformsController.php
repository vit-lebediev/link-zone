<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Entity\Platform;
use LinkZone\Core\PublicBundle\Form\Type\PlatformType;

class PlatformsController extends BaseController
{
    private $_platformRepository;
    private $_doctrineManager;
    private $_translator;
    private $_logger;
    private $_tagManager;
    private $_user;

    public function init()
    {
        $this->_platformRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Platform");
        $this->_doctrineManager    = $this->getDoctrine()->getManager();
        $this->_translator         = $this->get("translator");
        $this->_logger             = $this->get("logger");
        $this->_tagManager         = $this->get("fpn_tag.tag_manager");
        $this->_user               = $this->get("security.context")->getToken()->getUser();
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
        $this->_verifyIsXmlHttpRequest();

        if ($platformId = $request->get("platform_id")) {
            $platform = $this->_platformRepository->find($platformId);
            $this->_tagManager->loadTagging($platform);
            $platformTags = $platform->getTags();
        } else {
            $platform = new Platform();
        }

        $platformDialog = $this->createForm(new PlatformType(), $platform, array(
            'container' => $this->container,
        ));

        return $this->render("LinkZoneCorePublicBundle:Platforms:partials/platform_dialog.html.twig", array(
            'platformDialog' => $platformDialog->createView(),
            'platformTags'   => $platformTags,
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
        $this->_verifyIsXmlHttpRequest();

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

            if ($rawTags = mb_strtolower($this->getRequest()->get("platform")['tags']))
            {
                $tags = $this->_tagManager->loadOrCreateTags($this->_tagManager->splitTagNames($rawTags));
                $this->_tagManager->addTags($tags, $platform);
            }

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            // Note: ALWAYS save tags after the main entity was saved (persisted & flushed)
            $this->_tagManager->saveTagging($platform);

            return new JsonResponse();
        } else {
            $this->_logger->err($platformDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }

    public function ajaxEditPlatformAction($platformId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        if (!$platform = $this->_platformRepository->find($platformId)) {
            throw new BadRequestHttpException("There is no platform with id " . $platformId);
        }

        if ($platform->getOwner()->getId() != $this->_user->getId()) {
            throw new BadRequestHttpException("You can change only platforms, which belong to you");
        }

        $platformDialog = $this->createForm(new PlatformType(), $platform, array(
            'container' => $this->container,
        ));

        $platformDialog->bind($request);

        if ($platformDialog->isValid())
        {
            if ($rawTags = mb_strtolower($this->getRequest()->get("platform")['tags']))
            {
                $tags = $this->_tagManager->loadOrCreateTags($this->_tagManager->splitTagNames($rawTags));
                $this->_tagManager->replaceTags($tags, $platform);
            }

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            // Note: ALWAYS save tags after the main entity was saved (persisted & flushed)
            $this->_tagManager->saveTagging($platform);

            return new JsonResponse();
        } else {
            $this->_logger->err($platformDialog->getErrorsAsString());
            throw new BadRequestHttpException("Provided platform data is not valid");
        }
    }

    public function ajaxGetTagsAutocompleteAction()
    {
        $this->_verifyIsXmlHttpRequest();

        $tagRepo = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Tag");

        return new JsonResponse($tagRepo->getTagsStartingWithPrefix(Platform::TAGGABLE_TYPE, mb_strtolower($this->getRequest()->get("term"))));
    }
}
