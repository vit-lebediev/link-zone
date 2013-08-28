<?php

namespace LinkZone\Core\PublicBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends FOSSecurityController
{
    public function loginPartialAction()
    {
        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return $this->container->get('templating')->renderResponse("LinkZoneCorePublicBundle:Default:partials/login.html.twig", array(
            'csrf_token' => $csrfToken,
        ));
    }
}
