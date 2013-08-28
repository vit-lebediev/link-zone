<?php

namespace LinkZone\Core\PublicBundle\Manager;

use LinkZone\Core\PublicBundle\Entity\Platform as PlatformEntity; // Platfrom interferes with Manager class name (Platform, too)

use Symfony\Component\DependencyInjection\ContainerAware;

class Platform extends ContainerAware
{
    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Platform $platform
     * @return type
     */
    public function toArray(PlatformEntity $platform) {
        $translator = $this->container->get("translator");

        return array(
            'id' => $platform->getId(),
            'url' => $platform->getUrl(),
            'description' => $platform->getDescription(),
            'status_code' => $platform->getStatus(),
            'status_string' => $translator->trans("platforms.statuses." . $platform->getStatus(), array(), "LZCorePublicBundle"),
            'created' => $platform->getCreated()->format($this->container->getParameter("default_date_format")),
            'hidden' => $platform->getHidden() ? true : false,
            'topic_id' => ($platform->getTopic()) ? $platform->getTopic()->getId() : null,
            'topic' => ($platform->getTopic()) ? $platform->getTopic()->getDescription() : null,
            'owner' => array(
                'username' => $platform->getOwner()->getUsername(),
                'lastLogin' => $platform->getOwner()->getLastLogin()->format($this->container->getParameter("default_date_format")),
            )
        );
    }
}
