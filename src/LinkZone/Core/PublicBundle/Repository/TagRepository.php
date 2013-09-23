<?php

namespace LinkZone\Core\PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Taggable\Entity\TagRepository as BaseRepository;
/**
 * Tag Repository
 */
class TagRepository extends BaseRepository
{
    public function getTagsStartingWithPrefix($taggableType, $prefix, $maxResults = 10) {
        $qb = $this->getTagsQueryBuilder($taggableType);

        $results = $qb->andWhere($qb->expr()->like("tag." . $this->tagQueryField, ":prefix"))
                      ->setParameter("prefix", $prefix . "%")
                      ->select(["tag." . $this->tagQueryField, "tag.id"])
                      ->distinct(true)
                      ->setMaxResults($maxResults)
                      ->getQuery()
                      ->execute();

        $tags = array();
        foreach ($results as $result) {
            array_push($tags, [
                'text' => $result[$this->tagQueryField],
                'id'   => $result['id']
            ]);
        }

        return $tags;
    }
}
