<?php

namespace LinkZone\Core\PublicBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ClientErrorResponseException as HttpClientErrorResponseException;

use LinkZone\Core\PublicBundle\Exception\PlatformConfirmationException;

use LinkZone\Core\PublicBundle\Entity\Platform as PlatformEntity; // Platfrom interferes with Manager class name (Platform, too)

class Platform extends ContainerAware
{
    private $_logger;
    private $_translator;
    private $_doctrineManager;

    public function init()
    {
        $this->_logger = $this->container->get("logger");
        $this->_translator = $this->container->get("translator");
        $this->_doctrineManager = $this->container->get('doctrine')->getManager();
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @throws PlatformConfirmationException
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function confirmPlatformWithHtmlTag(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $htmlFileName = sprintf($this->container->getParameter("confirmation_html_file_name_format"), $platform->getConfirmationCode());

            $response = $httpClient->get("/{$htmlFileName}")->send();

            $asString = true;
            $domCrawler = new DomCrawler($response->getBody($asString));

            $remoteConfirmationCode = $domCrawler->filterXPath("//body/span[@id='{$this->container->getParameter("confirmation_code_string_name")}']")->text();

            if ($remoteConfirmationCode !== $platform->getConfirmationCode()) {
                $this->_logger->err("Confirmation code ({$remoteConfirmationCode}) for platform (ID: {$platform->getId()}), placed on remote host, is not correct. (Correct is: {$platform->getConfirmationCode()})");
                throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.invalid_confirmation_code_in_html_tag", array(), "LZCorePublicBundle"));
            }

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);
            $platform->setConfirmed(new \DateTime());

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Confirmation of platform with ID {$platform->getId()} with HTML TAG has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching confirmation HTML TAG file from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (PlatformConfirmationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            $this->_logger->err("Invalid HTML TAG file contents on platform confirmation (ID: {$platform->getId()})");
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.invalid_html_tag_file_contents", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed", array(), "LZCorePublicBundle"), 0, $e);
        }
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @throws PlatformConfirmationException
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function confirmPlatformWithMetaTag(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $response = $httpClient->get("/")->send();

            $asString = true;
            $domCrawler = new DomCrawler($response->getBody($asString));

            $remoteConfirmationCode = $domCrawler->filterXPath("//head/meta[@name='{$this->container->getParameter("linkzone_hostname")}-{$this->container->getParameter("confirmation_code_string_name")}']")->attr("content");

            if ($remoteConfirmationCode !== $platform->getConfirmationCode()) {
                $this->_logger->err("Confirmation code ({$remoteConfirmationCode}) for platform (ID: {$platform->getId()}), placed on remote host, is not correct. (Correct is: {$platform->getConfirmationCode()})");
                throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.invalid_confirmation_code_in_html_tag", array(), "LZCorePublicBundle"));
            }

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);
            $platform->setConfirmed(new \DateTime());

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Confirmation of platform with ID {$platform->getId()} with META TAG has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching confirmation META TAG data from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (PlatformConfirmationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            $this->_logger->err("Invalid META TAG file contents or missing meta tag on platform confirmation (ID: {$platform->getId()})");
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.cant_find_meta_tag", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed", array(), "LZCorePublicBundle"), 0, $e);
        }
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @throws PlatformConfirmationException
     */
    public function confirmPlatformWithTxtFile(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $htmlFileName = sprintf($this->container->getParameter("confirmation_txt_file_name_format"), $platform->getConfirmationCode());

            $httpClient->get("/{$htmlFileName}")->send();

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);
            $platform->setConfirmed(new \DateTime());

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Confirmation of platform with ID {$platform->getId()} with TXT FILE has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching confirmation TXT FILE file from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformConfirmationException($this->_translator->trans("platforms.errors.confirmation.failed", array(), "LZCorePublicBundle"), 0, $e);
        }
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @return type
     */
    public function toArray(PlatformEntity $platform)
    {
        $currentUser = $this->container->get("security.context")->getToken()->getUser();

        $platformArray = [
            'id' => $platform->getId(),
            'url' => $platform->getUrl(),
            'description' => $platform->getDescription(),
            'status_code' => $platform->getStatus(),
            'status_string' => $this->_translator->trans("platforms.statuses." . $platform->getStatus(), array(), "LZCorePublicBundle"),
            'created' => $platform->getCreated()->format($this->container->getParameter("default_date_format")),
            'hidden' => $platform->getHidden() ? true : false,
            'topic_id' => ($platform->getTopic()) ? $platform->getTopic()->getId() : null,
            'topic' => ($platform->getTopic()) ? $platform->getTopic()->getDescription() : null,
            'owner' => array(
                'username' => $platform->getOwner()->getUsername(),
                'lastLogin' => $platform->getOwner()->getLastLogin()->format($this->container->getParameter("default_date_format")),
            )
        ];

        if ($platform->getOwner() === $currentUser) {
            $platformArray['confirmation_code'] = $platform->getConfirmationCode();
        }

        return $platformArray;
    }
}
