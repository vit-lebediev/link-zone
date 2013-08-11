<?php

namespace LinkZone\Core\PublicBundle\Manager;

use LinkZone\Core\PublicBundle\Entity\Request as RequestEntity;

use Symfony\Component\DependencyInjection\ContainerAware;

class Request extends ContainerAware
{
    /**
     * Return formatted HTML for sender link
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     */
    public function getSenderLinkHTML(RequestEntity $request) {
        return sprintf($this->container->getParameter("default_link_html"), $request->getSenderLink(), $request->getSenderLinkText());
    }

    public function toArray(RequestEntity $request) {
        $translator = $this->container->get("translator");

        return array(
            'id' => $request->getId(),
            'senderLink' => $request->getSenderLink(),
            'senderLinkText' => $request->getSenderLinkText(),
            'senderLinkLocation' => $request->getSenderLinkLocation(),
            'senderLinkHTML'     => $this->getSenderLinkHTML($request),
            'receiverLink'       => $request->getReceiverLink(),
            'receiverLinkText'   => $request->getReceiverLinkText(),
            'receiverLinkLocation' => $request->getReceiverLinkLocation(),
            'senderPlatform' => array(
                'id' => $request->getSenderPlatform()->getId(),
                'url' => $request->getSenderPlatform()->getUrl(),
                'description' => $request->getSenderPlatform()->getDescription(),
                'owner' => array(
                    'username' => $request->getSenderPlatform()->getOwner()->getUsername(),
                ),
            ),
            'receiverPlatform' => array(
                'id' => $request->getReceiverPlatform()->getId(),
                'url' => $request->getReceiverPlatform()->getUrl(),
                'description' => $request->getReceiverPlatform()->getDescription(),
                'owner' => array(
                    'username' => $request->getReceiverPlatform()->getOwner()->getUsername(),
                ),
            ),
        );
    }
}
