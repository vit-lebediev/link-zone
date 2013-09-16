<?php

namespace LinkZone\Core\PublicBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FormEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\DependencyInjection\Helper\Utils;

/**
 * Listener responsible to fill required fields for newly created user
 */
class RegistrationListener extends ContainerAware implements EventSubscriberInterface
{
    public function __construct(UrlGeneratorInterface $router) {
        $this->_router = $router;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => "onRegistrationInitialize",
            FOSUserEvents::REGISTRATION_SUCCESS    => "onRegistrationSuccess",
        );
    }

    public function onRegistrationInitialize(UserEvent $event)
    {
        $user = $event->getUser();
        $request = $event->getRequest();
        $userRepository = $this->container->get("doctrine")->getRepository("LinkZoneCorePublicBundle:User");

        $user->setBallance(0);
        $user->setBonus(0);
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setRegistrationDate(new \DateTime());

        $randomValue = Utils::getRandomString();
        // ensure uniqueness of referral value
        while ($userRepository->findOneBy(array('referralValue' => $radnomValue))) {
            $radnomValue = Utils::getRandomString();
        }
        $user->setReferralValue($radnomValue);

        if ($referralValue = $request->get("referralValue") &&
                $referrer = $userRepository->findOneBy(array('referralValue' => $request->get("referralValue"))))
        {
                $user->setReferrer($referrer);
        }
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $event->setResponse(new RedirectResponse($this->_router->generate("fos_user_profile_show")));
    }

    private $_router;
}
