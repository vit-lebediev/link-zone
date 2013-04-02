<?php

namespace LinkZone\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ManageUsersController extends Controller
{
    public function listAction()
    {
        $userRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:list.html.twig", array('users' => $userRepository->findAll()));
    }

    public function specificAction($userId)
    {
        $userRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        $user = $userRepository->find($userId);

        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:specific.html.twig", array(
            'user' => $user,
        ));
    }

    /**
     * Ajax method to change user's email
     */
    public function ajaxChangeUserEmailAction($userId)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        $email = $this->getRequest()->get("email");

        $userRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        $user = $userRepository->find($userId);
        $user->setEmail($email);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush($user);

        return new JsonResponse();
    }
}
