<?php

namespace LinkZone\Core\PublicBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ClientErrorResponseException as HttpClientErrorResponseException;

use LinkZone\Core\PublicBundle\Exception\PlatformActivationException;

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
     * @throws PlatformActivationException
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function activatePlatformWithHtmlTag(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $htmlFileName = sprintf($this->container->getParameter("activation_html_file_name_format"), $platform->getActivationCode());

            $response = $httpClient->get("/{$htmlFileName}")->send();

            $asString = true;
            $domCrawler = new DomCrawler($response->getBody($asString));

            $remoteVerificationCode = $domCrawler->filterXPath("//body/span[@id='{$this->container->getParameter("verification_code_string_name")}']")->text();

            if ($remoteVerificationCode !== $platform->getActivationCode()) {
                $this->_logger->err("Activation code ({$remoteVerificationCode}) for platform (ID: {$platform->getId()}), places on remote host, is not correct. (Correct is: {$platform->getActivationCode()})");
                throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.invalid_verification_code_in_html_tag", array(), "LZCorePublicBundle"));
            }

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Activation of platform with ID {$platform->getId()} with HTML TAG has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching verification HTML TAG file from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (PlatformActivationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            $this->_logger->err("Invalid HTML TAG file contents on platform activation (ID: {$platform->getId})");
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.invalid_html_tag_file_contents", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed", array(), "LZCorePublicBundle"), 0, $e);
        }
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @throws PlatformActivationException
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function activatePlatformWithMetaTag(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $response = $httpClient->get("/")->send();

            $asString = true;
            $domCrawler = new DomCrawler($response->getBody($asString));

            $remoteVerificationCode = $domCrawler->filterXPath("//head/meta[@name='{$this->container->getParameter("linkzone_hostname")}-{$this->container->getParameter("verification_code_string_name")}']")->attr("content");

            if ($remoteVerificationCode !== $platform->getActivationCode()) {
                $this->_logger->err("Activation code ({$remoteVerificationCode}) for platform (ID: {$platform->getId()}), places on remote host, is not correct. (Correct is: {$platform->getActivationCode()})");
                throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.invalid_verification_code_in_html_tag", array(), "LZCorePublicBundle"));
            }

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Activation of platform with ID {$platform->getId()} with META TAG has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching verification META TAG data from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (PlatformActivationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            $this->_logger->err("Invalid META TAG file contents or missing meta tag on platform activation (ID: {$platform->getId()})");
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.cant_find_meta_tag", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed", array(), "LZCorePublicBundle"), 0, $e);
        }
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @throws PlatformActivationException
     */
    public function activatePlatformWithTxtFile(PlatformEntity $platform)
    {
        try {
            $httpClient = new HttpClient($platform->getUrl());

            $htmlFileName = sprintf($this->container->getParameter("activation_txt_file_name_format"), $platform->getActivationCode());

            $httpClient->get("/{$htmlFileName}")->send();

            $platform->setStatus(PlatformEntity::STATUS_ON_MODERATION);

            $this->_doctrineManager->persist($platform);
            $this->_doctrineManager->flush();

            $this->_logger->info("Activation of platform with ID {$platform->getId()} with TXT FILE has completed successfully.");
        } catch (HttpClientErrorResponseException $e) {
            $this->_logger->err("Failed fetching verification TXT FILE file from platform {$platform->getUrl()} (ID: {$platform->getId()})");
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed_fetch_file", array(), "LZCorePublicBundle"), 0, $e);
        } catch (\Exception $e) {
            throw new PlatformActivationException($this->_translator->trans("platforms.errors.activation.failed", array(), "LZCorePublicBundle"), 0, $e);
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
            $platformArray['activation_code'] = $platform->getActivationCode();
        }

        return $platformArray;
    }
}
