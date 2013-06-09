<?php

namespace LinkZone\Core\PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Taggable\Entity\TagRepository as BaseRepository;
/**
 * Tag Repository
 */
class TagRepository extends BaseRepository
{
    public function getTagsStartingWithPrefix($taggableType, $prefix) {
        $qb = $this->getTagsQueryBuilder($taggableType);

        $results = $qb->andWhere($qb->expr()->like("tag." . $this->tagQueryField, ":prefix"))
                      ->setParameter("prefix", $prefix . "%")
                      ->select("tag." . $this->tagQueryField)
                      ->distinct(true)
                      ->getQuery()
                      ->execute();

        $tags = array();
        foreach ($results as $result) {
            $tags[] = $result[$this->tagQueryField];
        }

        return $tags;
    }
}
