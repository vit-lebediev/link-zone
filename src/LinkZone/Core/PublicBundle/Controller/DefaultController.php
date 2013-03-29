<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LinkZoneCorePublicBundle:Default:index.html.twig');
    }
}
