<?php

namespace Yon\Bundle\ParisBundle\Manager;

abstract class BaseManager
{
    protected $queryBuilder;

    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function addSelect($select)
    {
        $qb = $this->getQueryBuilder()->addSelect($select);
        $this->setQueryBuilder($qb);

        return $this;
    }

    public function findBy($options=null, $limit=null, $sortings=null)
    {
        $qb = $this->getRepository()->findByQueryBuilder($options);
        $qb = $this->getRepository()->addLimit($qb, $limit);
        $qb = $this->getRepository()->addSortings($qb, $sortings);
        $this->setQueryBuilder($qb);

        return $this;
    }


    public function getOneOrNullResult()
    {
        return $this
            ->getQueryBuilder()
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Query::HYDRATE_OBJECT
     * Query::HYDRATE_ARRAY
     * Query::HYDRATE_SCALAR
     * Query::HYDRATE_SINGLE_SCALAR
     */
    public function getSingleResult($hydrate=\Doctrine\ORM\Query::HYDRATE_OBJECT)
    {
        return $this
            ->getQueryBuilder()
            ->getQuery()
            ->getSingleResult($hydrate);
    }

    /**
     * Query::HYDRATE_OBJECT
     * Query::HYDRATE_ARRAY
     * Query::HYDRATE_SCALAR
     * Query::HYDRATE_SINGLE_SCALAR
     */
    public function getResult($hydrate=\Doctrine\ORM\Query::HYDRATE_OBJECT)
    {
        return $this
            ->getQueryBuilder()
            ->getQuery()
            ->getResult($hydrate);
    }
}