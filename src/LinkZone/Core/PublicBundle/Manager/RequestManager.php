<?php

namespace LinkZone\Core\PublicBundle\Manager;

use LinkZone\Core\PublicBundle\Entity\Request;

use Symfony\Component\DependencyInjection\ContainerAware;

class RequestManager extends ContainerAware
{
    /**
     * Return formatted HTML for sender link
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $request
     */
    public function getSenderLinkHTML(Request $request) {
        return sprintf($this->container->getParameter("default_link_html"), $request->getSenderLink(), $request->getSenderLinkText());
    }
}
