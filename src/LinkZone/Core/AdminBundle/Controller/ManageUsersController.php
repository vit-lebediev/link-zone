<?php

namespace LinkZone\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ManageUsersController extends Controller
{
    private $_userRepository;

    private $_doctrineManager;

    private $_billingAvailable = array(
        "yadengy",
        "wmr",
        "wmz"
    );

    public function init()
    {
        $this->_userRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        $this->_doctrineManager = $this->getDoctrine()->getManager();
    }

    public function listAction()
    {
        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:list.html.twig", array('users' => $this->_userRepository->findAll()));
    }

    public function specificAction($userId)
    {
        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:specific.html.twig", array(
            'user' => $this->_userRepository->find($userId),
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

        $user = $this->_userRepository->find($userId);
        $user->setEmail($email);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush($user);

        return new JsonResponse();
    }

    public function ajaxChangeUserBillingAction($userId)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        if (!$billingType = $this->getRequest()->get("type") OR !in_array($billingType, $this->_billingAvailable)) {
            throw new BadRequestHttpException("You should specify valid 'type' value for billing type");
        }

        $billingValue = $this->getRequest()->get("value");

        $user = $this->_userRepository->find($userId);

        $methodName = "setBilling" . $billingType;
        $user->$methodName($billingValue);

        $this->_doctrineManager->persist($user);
        $this->_doctrineManager->flush($user);

        return new JsonResponse();
    }

    public function ajaxAddBonusAction($userId)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        $amount = $this->getRequest()->get("amount");
        $comment = $this->getRequest()->get("comment");

        $user = $this->_userRepository->find($userId);
        $user->setBonus($user->getBonus() + $amount);

        $this->_doctrineManager->persist($user);
        $this->_doctrineManager->flush($user);

        return new JsonResponse();
    }
}
