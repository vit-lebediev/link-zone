<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 24.09.13
 * Time: 22:56
 * To change this template use File | Settings | File Templates.
 */

namespace LinkZone\Core\PublicBundle\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

class TableOrmHelper
{
    /**
     * Helper settings
     */
    const REQUEST_SORT_PARAM_NAME = 'sorting';

    /**
     * @var QueryBuilder
     */
    private $_qb;

    /**
     * @var Request
     */
    private $_request;

    /**
     * Init helper
     *
     * @param Request $request
     * @param QueryBuilder $qb
     */
    public function __construct(Request $request, QueryBuilder $qb)
    {
        $this->_qb = $qb;
        $this->_request = $request;
    }

    /**
     * Add sorting for query
     *
     * @param array $sortFieldsAccepted
     * @param Logger $logger
     *
     * @throws \InvalidArgumentException
     */
    public function addSortingHelper(array $sortFieldsAccepted, Logger $logger)
    {
        // Sorting request format: array('fieldName'=>'direction')
        $sorting = $this->_request->get(self::REQUEST_SORT_PARAM_NAME, array());

        // Nothing to sort, request empty
        if (empty($sorting)) {
            return;
        }

        // Setup params
        $sortingFiledName = array_keys($sorting)[0];
        $sortingDirection = array_values($sorting)[0];

        // Check if sort field in allowed fields list
        if (!in_array($sortingFiledName, $sortFieldsAccepted)) {
            throw new \InvalidArgumentException('Bad sorting key, sorting key not in allowed list');
        }

        // Check bad order string
        if (!in_array($sortingDirection, array('asc', 'desc'))) {
            throw new \InvalidArgumentException('Bad sorting direction param, it must be "asc" or "desc" only');
        }

        // Do sort
        $this->_qb->orderBy($sortingFiledName, $sortingDirection);
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->_qb;
    }

    /**
     * @return ArrayCollection
     */
    public function executeQuery()
    {
        return new ArrayCollection($this->_qb->getQuery()->execute());
    }
}