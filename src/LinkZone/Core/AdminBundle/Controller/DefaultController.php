<?php

namespace LinkZone\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//        return $this->render('LinkZoneCoreAdminBundle:Default:index.html.twig', array('name' => $name . " From LinkZone"));
        return $this->render('LinkZoneCoreAdminBundle:Default:index.html.twig');
    }
}
