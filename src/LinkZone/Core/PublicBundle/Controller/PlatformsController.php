<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Entity\Platform;
use LinkZone\Core\PublicBundle\Form\Type\PlatformType;
use LinkZone\Core\PublicBundle\Form\Type\Platform\Search\PlatformType as PlatformSearchFilter;

class PlatformsController extends BaseController
{
    private $_tagManager;

    public function init()
    {
        parent::_init();

        $this->_tagManager         = $this->get("fpn_tag.tag_manager");
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

    public function searchAction(Request $request)
    {
        $thereIsFilter      = false;
        $lastLogin          = null;
        $filter             = array();
        $platformSearchTags = array();

        $platform = new Platform();
        if ($topicId = $request->get("platform_search")['topic'] AND is_numeric($topicId))
        {
            $platform->setTopic($this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:PlatformTopic")->find($topicId));
            $thereIsFilter = true;
            $filter['topicId'] = $topicId;
        }

        if (isset($request->get("platform_search")['owner']['lastLogin'])
                AND $lastLogin = $request->get("platform_search")['owner']['lastLogin']
                AND !empty($lastLogin))
        {
            $thereIsFilter = true;
            $filter['lastLogin'] = $lastLogin;
        }

        if ($rawTags = mb_strtolower($request->get("platform_tags")) AND strlen($rawTags) > 0)
        {
            $thereIsFilter = true;
            $tags = $this->_tagManager->loadOrCreateTags($this->_tagManager->splitTagNames($rawTags));
            $filter['platformSearchTags'] = $tags;
        }

        $platformSearchFilter = $this->createForm(new PlatformSearchFilter(), $platform, array(
            'container' => $this->container,
            'lastLogin' => $lastLogin,
        ));

        if ($thereIsFilter) {
            $platforms = $this->_platformRepository->findAllByFilterExceptForUser($this->_user, $filter);
        } else {
            $platforms = $this->_platformRepository->findAllNotHiddenExceptForUser($this->_user);
        }

        return $this->render("LinkZoneCorePublicBundle:Platforms:search.html.twig", array(
            'platforms' => $platforms,
            'platformSearchFilter' => $platformSearchFilter->createView(),
            'platformSearchTags' => $platformSearchTags,
        ));
    }

    /**
     * Ajax handlers
     */

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
