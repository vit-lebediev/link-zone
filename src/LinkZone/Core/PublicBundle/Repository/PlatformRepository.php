<?php

namespace LinkZone\Core\PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PlatformRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlatformRepository extends EntityRepository
{
    public function findAll() {
        return new ArrayCollection($this->findBy(array(), array('created' => "DESC")));
    }

    public function findAllNotHidden()
    {
        return new ArrayCollection($this->findBy(
                array('hidden' => false),
                array('created' => "DESC")
        ));
    }

    public function findAllNotHiddenExceptForUser($user)
    {
        $qb = $this->createQueryBuilder("platform");
        $qb->where($qb->expr()->andx(
                $qb->expr()->eq("platform.hidden", ":hidden"),
                $qb->expr()->not($qb->expr()->eq("platform.owner", ":owner"))
        ));

        $qb->setParameter(":hidden", 0);
        $qb->setParameter(":owner", $user);

        $qb->orderBy("platform.created", "DESC");

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     *
     *
     * @param type $user
     * @param array $filter
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllByFilterExceptForUser($user, array $filter) {
        $qb = $this->createQueryBuilder("platform");

        $andCondition = $qb->expr()->andx();

        // apply filters
        foreach ($filter as $option => $value) {
            switch ($option) {
                case "topicId":
                    $andCondition->add($qb->expr()->eq("platform.topic", ":topicId"));
                    $qb->setParameter(":topicId", $value);
                    break;
                case "lastLogin":
                    $qb->leftJoin("platform.owner", "owner");
                    $andCondition->add($qb->expr()->gte("owner.lastLogin", ":lastLogin"));
                    $qb->setParameter(":lastLogin", new \DateTime($value));
                    break;
                case "platformSearchTags":
                    break;
                default: throw new \Exception("Wrong filter");
            }
        }

        // filter out current user platforms
        $andCondition->add($qb->expr()->not($qb->expr()->eq("platform.owner", ":owner")));
        $qb->setParameter(":owner", $user);

        // filter out hidden platforms
        $andCondition->add($qb->expr()->eq("platform.hidden", ":hidden"));
        $qb->setParameter(":hidden", 0);

        $qb->where($andCondition);

        $qb->orderBy("platform.created", "DESC");

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    private function _getNotHiddenExceptForUserAndCondition($qb)
    {
        return $qb->expr()->andx(
                $qb->expr()->eq("platform.hidden", ":hidden"),
                $qb->expr()->not($qb->expr()->eq("platform.owner", ":owner"))
        );
    }

    /**
     *
     * @param array $dateArray A date array of format:
     *                  $dateArray['date']['year']   --> Year
     *                  $dateArray['date']['month']  --> Month (Starts from 1, i.e. Jan is 1)
     *                  $dateArray['date']['day']    --> Day
     *                  $dateArray['time']['hour']   --> Hour (00-23)
     *                  $dateArray['time']['minute'] --> Minute (00-59)
     */
    private function _parseDateArrayToString(array $dateArray)
    {
        $dateString = null;

        if (isset($dateArray['date']['year'])
                AND !empty($dateArray['date']['year']))
        {
            $dateString .= $dateArray['date']['year'];
        } else {
            $dateString .= date("Y");
        }

        $dateString .= "-";

        if (isset($dateArray['date']['month'])
                AND !empty($dateArray['date']['month']))
        {
            $dateString .= $dateArray['date']['month'];
        } else {
            $dateString .= "01";
        }

        $dateString .= "-";

        if (isset($dateArray['date']['day'])
                AND !empty($dateArray['date']['day']))
        {
            $dateString .= $dateArray['date']['day'];
        } else {
            $dateString .= "01";
        }

        $dateString .= " ";

        if (isset($dateArray['time']['hour'])
                AND !empty($dateArray['time']['hour']))
        {
            $dateString .= $dateArray['time']['hour'];
        } else {
            $dateString .= "00";
        }

        $dateString .= ":";

        if (isset($dateArray['time']['minute'])
                AND !empty($dateArray['time']['minute']))
        {
            $dateString .= $dateArray['time']['minute'];
        } else {
            $dateString .= "00";
        }

        $dateString .= ":00";

        return $dateString;
    }
}
