<?php

namespace LinkZone\Core\PublicBundle\Repository;

use LinkZone\Core\PublicBundle\Entity\Request;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\UserInterface;

/**
 * PlatformRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RequestRepository extends EntityRepository
{
    public function findAllReceivedForExchangeForUser(UserInterface $user)
    {
        return new ArrayCollection($this->findBy(
                array(
                    'receiverUser' => $user,
                    'status'       => Request::STATUS_EXCHANGE,
                ),
                array('created' => "DESC")
        ));
    }

    public function findAllSentForExchangeForUser(UserInterface $user)
    {
        return new ArrayCollection($this->findBy(
                array(
                    'senderUser' => $user,
                    'status'     => Request::STATUS_EXCHANGE,
                ),
                array('created' => "DESC")
        ));
    }
}
