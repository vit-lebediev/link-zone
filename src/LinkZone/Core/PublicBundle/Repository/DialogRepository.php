<?php

namespace LinkZone\Core\PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\UserInterface;

/**
 * DialogRepository
 */
class DialogRepository extends EntityRepository
{
    public function findAll() {
        return new ArrayCollection($this->findBy(array(), array('created' => "DESC")));
    }

    public function findForPlatforms($messageSenderPlatform, $messageReceiverPlatform)
    {
        $qb = $this->createQueryBuilder("d");

        $qb->where($qb->expr()->orx(
                $qb->expr()->andx(
                        $qb->expr()->eq("d.senderPlatform", ":messageSenderPlatform"),
                        $qb->expr()->eq("d.receiverPlatform", ":messageReceiverPlatform")
                ),
                $qb->expr()->andx(
                        $qb->expr()->eq("d.senderPlatform", ":messageReceiverPlatform"),
                        $qb->expr()->eq("d.receiverPlatform", ":messageSenderPlatform")
                )
        ));

        $qb->setParameter(":messageSenderPlatform", $messageSenderPlatform);
        $qb->setParameter(":messageReceiverPlatform", $messageReceiverPlatform);

        $qb->setMaxResults(1);

        $results = $qb->getQuery()->getResult();

        return array_shift($results);
    }

    public function findAllForUser(UserInterface $user)
    {
        $qb = $this->createQueryBuilder("d");

        $qb->where($qb->expr()->orx(
                $qb->expr()->eq("d.senderUser", ":user"),
                $qb->expr()->eq("d.receiverUser", ":user")
        ));

        return $qb->setParameter(":user", $user)
                  ->orderBy("d.updated", "DESC")
                  ->setMaxResults(25) // TODO: Fix this to achieve pagination
                  ->getQuery()
                  ->getResult();
    }

    public function findForUser($dialogId, UserInterface $user)
    {
        $qb = $this->createQueryBuilder("d");

        $qb->where($qb->expr()->andx(
                $qb->expr()->orx(
                        $qb->expr()->eq("d.senderUser", ":user"),
                        $qb->expr()->eq("d.receiverUser", ":user")
                ),
                $qb->expr()->eq("d.id", ":dialogId")
        ));

        $results = $qb->setParameter(":user", $user)
                      ->setParameter(":dialogId", $dialogId)
                      ->setMaxResults(1)
                      ->getQuery()
                      ->getResult();

        return array_shift($results);
    }
}
