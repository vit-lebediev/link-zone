<?php

namespace LinkZone\Core\AdminBundle\Controller;

// import core Symfony components
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

// import exceptions
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\ORMInvalidArgumentException;

// import project components
use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\AdminBundle\Entity\LogStatusChangesEntry;

class ManageUsersController extends Controller
{
    private $_userRepository;
    private $_doctrineManager;
    private $_translator;
    private $_logger;

    private $_billingAvailable = array(
        "yadengy",
        "wmr",
        "wmz"
    );

    public function init()
    {
        $this->_userRepository  = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:User");
        $this->_doctrineManager = $this->getDoctrine()->getManager();
        $this->_translator      = $this->get('translator');
        $this->_logger          = $this->get('logger');
    }

    /**
     * List all the users
     *
     * @return type
     */
    public function listAction()
    {
        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:list.html.twig", array('users' => $this->_userRepository->findBy(
                array(),
                array('registrationDate' => 'DESC')
        )));
    }

    /**
     * Show information about specific user
     *
     * @param int $userId
     * @return type
     */
    public function specificAction($userId)
    {
        if (!$user = $this->_userRepository->find($userId)) {
            throw new BadRequestHttpException("There is no user with ID " . $userId);
        }
        $statusDropDown = $this->createFormBuilder($user)
                     ->add("status", "choice", array(
                         'choices' => array(
                             User::STATUS_ACTIVE          => $this->_translator->trans("user_management.statuses." . User::STATUS_ACTIVE,          array(), "LZCoreAdminBundle"),
                             User::STATUS_ACCOUNT_BLOCKED => $this->_translator->trans("user_management.statuses." . User::STATUS_ACCOUNT_BLOCKED, array(), "LZCoreAdminBundle"),
                             User::STATUS_DELETED         => $this->_translator->trans("user_management.statuses." . User::STATUS_DELETED,         array(), "LZCoreAdminBundle"),
                             User::STATUS_BLOCKED         => $this->_translator->trans("user_management.statuses." . User::STATUS_BLOCKED,         array(), "LZCoreAdminBundle"),
                             User::STATUS_PASSIVE         => $this->_translator->trans("user_management.statuses." . User::STATUS_PASSIVE,         array(), "LZCoreAdminBundle"),
                         ),
//                         'data' => $user->getStatus(),
                         // leave this here for future reference
//                         'attr' => array(
//                             'class' => 'user_status',
//                         ),
                     ))->getForm();
        return $this->render("LinkZoneCoreAdminBundle:ManageUsers:specific.html.twig", array(
            'user' => $user,
            'statusDropDown' => $statusDropDown->createView(),
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
        $this->_doctrineManager->persist($user);
        $this->_doctrineManager->flush($user);

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

    public function ajaxSetStatusAction($userId)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        $user = $this->_userRepository->find($userId);
        $status = $this->getRequest()->get("status");
        $prevStatus = $user->getStatus();
        $reasonMessage = $this->getRequest()->get("reason");
        if (strlen($reasonMessage) == 0) {
            throw new BadRequestHttpException("You must specify reason for status change");
        }
        try {
            $user->setStatus($status);
            $this->_doctrineManager->persist($user);
        } catch (ORMInvalidArgumentException $e) {
            $this->_logger->err("Invalid user status provided (" . $status . ")");
            throw new BadRequestHttpException();
//            return (new JsonResponse())->setStatusCode(500);
        }

        $logStatusEntry = new LogStatusChangesEntry();
        $logStatusEntry->setDate(new \DateTime());
        $logStatusEntry->setFromStatus($prevStatus);
        $logStatusEntry->setToStatus($status);
        $logStatusEntry->setReason($reasonMessage);
        $logStatusEntry->setWhoChanged($this->get('security.context')->getToken()->getUser()->getUsername());
        $logStatusEntry->setUserId($userId);
        $this->_doctrineManager->persist($logStatusEntry);

        $this->_doctrineManager->flush($user);

        return new JsonResponse();
    }

    public function ajaxResetPasswordAction($userId)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }

        $this->getRequest()->request->set('username', $this->_userRepository->find($userId)->getUsername());
        $response = $this->forward("FOSUserBundle:Resetting:sendEmail", array(
            'request' => $this->getRequest(),
        ));
        return new JsonResponse();
    }
}
