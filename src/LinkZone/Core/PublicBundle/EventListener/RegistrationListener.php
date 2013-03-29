<?php

namespace LinkZone\Core\PublicBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible to fill required fields for newly created user
 */
class RegistrationListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => "onRegistrationInitialize"
        );
    }

    public function onRegistrationInitialize(UserEvent $event)
    {
        $user = $event->getUser();
        $request = $event->getRequest();

        $user->setBallance(0);
        $user->setStatus("ACTIVE");
        $user->setRegistrationDate(new \DateTime());
    }
}
