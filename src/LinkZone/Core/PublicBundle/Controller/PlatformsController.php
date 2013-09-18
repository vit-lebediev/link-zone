<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use LinkZone\Core\PublicBundle\Exception\PlatformActivationException;

use LinkZone\Core\PublicBundle\Entity\Platform;
use LinkZone\Core\PublicBundle\Form\Type\PlatformType;
use LinkZone\Core\PublicBundle\Entity\PlatformTopic;
use LinkZone\Core\PublicBundle\Form\Type\Platform\Search\PlatformType as PlatformSearchFilter;
use LinkZone\Core\PublicBundle\DependencyInjection\Helper\Utils;

class PlatformsController extends BaseController
{
    private $_tagManager;
    private $_platformManager;
    private $_platformTopicRepository;

    public function init()
    {
        parent::_init();

        $this->_tagManager      = $this->get("fpn_tag.tag_manager");
        $this->_platformManager = $this->get("link_zone.core.public.manager.platform");
        $this->_platformTopicRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:PlatformTopic");
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

    public function ajaxApiSearchPlatformsAction(Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        $thereIsFilter      = false;
        $lastLogin          = null;
        $filter             = array();
        $platformSearchTags = array();

        $platform = new Platform();
        if ($topicId = $request->get("topicId") AND is_numeric($topicId))
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

        $platformArray = array();

        foreach ($platforms as $platform) {
            $platformArray[] = $this->_platformManager->toArray($platform);
        }

        return new JsonResponse($platformArray);

        return $this->render("LinkZoneCorePublicBundle:Platforms:search.html.twig", array(
            'platforms' => $platforms,
            'platformSearchFilter' => $platformSearchFilter->createView(),
            'platformSearchTags' => $platformSearchTags,
        ));
    }

    /**
     * Ajax handlers
     */

    public function ajaxPlatformDialogAction($action, Request $request)
    {
        $platformTags = array();

        $this->_verifyIsXmlHttpRequest();

        if (!in_array($action, array('edit', 'add')))
                throw new BadRequestHttpException("Requesting for non-existing template");

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

        return $this->render("LinkZoneCorePublicBundle:Platforms:partials/" . $action . "_dialog.html.twig", array(
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
            $platform->setStatus(Platform::STATUS_NOT_CONFIRMED)
                     ->setCreated(new \DateTime())
                     ->setOwner($this->_user)
                     ->setActivationCode(Utils::getRandomString(24));

            $reqeustPlatform = $this->getRequest()->get("platform");

            if (isset($reqeustPlatform['tags']) AND $rawTags = mb_strtolower($reqeustPlatform['tags']))
            {
                $tags = $this->_tagManager->loadOrCreateTags($this->_tagManager->splitTagNames($rawTags));
                $this->_tagManager->addTags($tags, $platform);
            }

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            // Note: ALWAYS save tags after the main entity was saved (persisted & flushed)
            $this->_tagManager->saveTagging($platform);

            return new JsonResponse(array(
                'platform' => $this->_platformManager->toArray($platform),
            ));
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

    public function ajaxApiListPlatformsAction()
    {
        $this->_verifyIsXmlHttpRequest();

        $platformArray = array();

        foreach ($this->_user->getPlatforms() as $platform) {
            $platformArray[] = $this->_platformManager->toArray($platform);
        }

        return new JsonResponse($platformArray);
    }

    public function ajaxApiPlatformAction($platformId)
    {
        $this->_verifyIsXmlHttpRequest();

        if ($platform = $this->_platformRepository->find($platformId)) {
            // todo: check if user has access to this platform
            return new JsonResponse($this->_platformManager->toArray($platform));
        } else {
            return new JsonResponse(array(
                'message' => $this->_translator->trans("platforms.errors.no_platform", array(), "LZCorePublicBundle"),
            ), 404);
        }
    }

    public function ajaxApiEditPlatformAction($platformId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        if ($platform = $this->_platformRepository->find($platformId)) {
            // TODO: check if user has access to this platform

            $requestData = json_decode($request->getContent(), true);

            if ($requestData['topic_id'] AND !$topic = $this->_platformTopicRepository->find($requestData['topic_id'])) {
                throw new BadRequestHttpException("You are trying to edit topic which doesn't exists");
            }

            if (isset($topic)) {
                $platform->setTopic($topic);
            } else {
                $platform->setTopic(null);
            }

            $platform->setDescription($requestData['description']);
            $platform->setHidden($requestData['hidden']);

            // TODO: add tags

            // validate
            $errors = $this->_validator->validate($platform);

            if (count($errors) > 0) {
                return new JsonResponse(array('errors' => $this->_parseValidationErrors($errors)), 400);
            }
            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();
            return new JsonResponse($this->_platformManager->toArray($platform));
        } else {
            return new JsonResponse(array(
                'message' => $this->_translator->trans("platforms.errors.no_platform", array(), "LZCorePublicBundle"),
            ), 404);
        }
    }

    public function ajaxSearchFilterPartialAction()
    {
        $this->_verifyIsXmlHttpRequest();

        $platformSearchFilter = $this->createForm(new PlatformSearchFilter(), new Platform(), array(
            'container' => $this->container,
            'lastLogin' => null,
        ));

        return $this->render("LinkZoneCorePublicBundle:Platforms:partials/search-filter.html.twig", array(
            'platformSearchFilter' => $platformSearchFilter->createView(),
        ));
    }

    public function apiActivatePlatformAction($platformId, Request $request)
    {
        $this->_verifyIsXmlHttpRequest();

        // check that platform exists
        $platform = $this->_platformRepository->find($platformId);
        if (!$platform) {
            $this->_logger->warn("User {$this->_user->getUsername()} (ID: {$this->_user->getId()}) tried to activate non existing platform (ID: {$platformId})");
            return new JsonResponse(array(
                'message' => $this->_translator->trans("platforms.errors.no_platform", array(), "LZCorePublicBundle"),
            ), 404);
        }

        if ($platform->getOwner() !== $this->_user) {
            $this->_logger->warn("User {$this->_user->getUsername()} (ID: {$this->_user->getId()}) tried to activate platfrom (ID: {$platform->getId()}), which belongs to user {$platform->getOwner()->getUsername()} (ID: {$platform->getOwner()->getId()}");
            // TODO: throw exception
            return new JsonResponse(array(
                'message' => $this->_translator->trans("platforms.errors.no_platform", array(), "LZCorePublicBundle"),
            ), 404);
        }

        if ($platform->getStatus() !== Platform::STATUS_NOT_CONFIRMED) {
            $this->_logger->warn("User {$this->_user->getUsername()} (ID: {$this->_user->getId()}) tried to activate already activated platform (ID: {$platformId})");
            return new JsonResponse(array(
                'message' => $this->_translator->trans("platforms.errors.activation.already_activated", array(), "LZCorePublicBundle"),
            ), 404);
        }

        $this->_logger->info("User {$this->_user->getUsername()} (ID: {$this->_user->getId()}) started activation process on platform {$platform->getUrl()} (ID: {$platform->getId()})");

        // check activation according to the choosen activation way
        try {
            $requestData = json_decode($request->getContent(), true);

            switch ($requestData['activationMethod']) {
                case Platform::ACTIVATION_METHOD_HTML_TAG:
                    $this->_platformManager->activatePlatformWithHtmlTag($platform);
                    break;
                case Platform::ACTIVATION_METHOD_META_TAG:
                    $this->_platformManager->activatePlatformWithMetaTag($platform);
                    break;
                case Platform::ACTIVATION_METHOD_TXT_FILE:
                    $this->_platformManager->activatePlatformWithTxtFile($platform);
                    break;
                default:
                    throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.invalid_method", array(), "LZCorePublicBundle"));
            }

            return new JsonResponse([
                'new_status_code' => $platform->getStatus(),
                'new_status_string' => $this->_translator->trans("platforms.statuses.{$platform->getStatus()}", array(), "LZCorePublicBundle")
            ]);
        } catch (PlatformActivationException $e) {
            return new JsonResponse(array(
                'error_message' => $e->getMessage(),
            ), 400);
        }
    }
}
