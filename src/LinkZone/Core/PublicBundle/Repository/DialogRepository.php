<?php

namespace LinkZone\Core\PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;

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
}