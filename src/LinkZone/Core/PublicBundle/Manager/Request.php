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

    /**
     * Return formatted HTML for receiver link
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     */
    public function getReceiverLinkHTML(RequestEntity $request) {
        return sprintf($this->container->getParameter("default_link_html"), $request->getReceiverLink(), $request->getReceiverLinkText());
    }

    public function toArray(RequestEntity $request) {
        $translator = $this->container->get("translator");
        $currentUser = $this->container->get("security.context")->getToken()->getUser();

        return [
            'id' => $request->getId(),
            'senderLink' => $request->getSenderLink(),
            'senderLinkText' => $request->getSenderLinkText(),
            'senderLinkLocation' => $request->getSenderLinkLocation(),
            'senderLinkHTML'     => $this->getSenderLinkHTML($request),
            'senderAccepted'     => $request->getSenderAccepted(),
            'receiverLink'       => $request->getReceiverLink(),
            'receiverLinkText'   => $request->getReceiverLinkText(),
            'receiverLinkLocation' => $request->getReceiverLinkLocation(),
            'receiverLinkHTML'     => $this->getReceiverLinkHTML($request),
            'receiverAccepted'     => $request->getReceiverAccepted(),
            'isIncoming'           => ($request->getReceiverPlatform()->getOwner() === $currentUser) ? true : false,
            'created'              => $request->getCreated()->format($this->container->getParameter("default_date_format")),
            'finished'             => $request->getFinished() ? $request->getFinished()->format($this->container->getParameter("default_date_format")) : null,
            'senderPlatform'       => array(
                'id' => $request->getSenderPlatform()->getId(),
                'url' => $request->getSenderPlatform()->getUrl(),
                'description' => $request->getSenderPlatform()->getDescription(),
                'owner' => array(
                    'username' => $request->getSenderPlatform()->getOwner()->getUsername(),
                ),
            ),
            'receiverPlatform'     => array(
                'id' => $request->getReceiverPlatform()->getId(),
                'url' => $request->getReceiverPlatform()->getUrl(),
                'description' => $request->getReceiverPlatform()->getDescription(),
                'owner' => array(
                    'username' => $request->getReceiverPlatform()->getOwner()->getUsername(),
                ),
            ),
        ];
    }
}
