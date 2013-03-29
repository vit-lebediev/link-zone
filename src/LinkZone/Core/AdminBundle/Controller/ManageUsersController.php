<?php

namespace LinkZone\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManageUsersController extends Controller
{
    public function listAction()
    {
        $doctrine = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        return $this->render('LinkZoneCoreAdminBundle:ManageUsers:list.html.twig', array('users' => $doctrine->findAll()));
    }

    public function specificAction($userId)
    {
    }
}
