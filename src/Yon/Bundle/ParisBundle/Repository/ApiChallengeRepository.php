<?php

namespace Yon\Bundle\ParisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;

class ApiChallengeRepository extends EntityRepository
{
    public function findByQueryBuilder($options=null)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.user', 'pu')
            ->leftJoin('p.hashtag', 'ph')
            ->leftJoin('p.contestChallenge', 'pcc')
            ->leftJoin('pcc.contest', 'pc');
//        $qb->andWhere('s.deleted = 0');

        if (isset($options['status']) && $options['status'] != "") {
            
                $qb
                ->andWhere('p.status = :status')
                ->setParameter('status', $options['status']);
            
        } else {
            echo $options['status'];
            
            if( $options['status'] === 0){
                 $qb
                ->andWhere('p.status = :status')
                ->setParameter('status', $options['status']);
            }
        }

        if (isset($options['search'])) {
            $qb
                ->andWhere('p.id = :searchId OR pu.username like :searchName OR p.title like :searchName OR ph.tag like :searchName' )
                ->setParameter('searchId', $options['search'])
                ->setParameter('searchName', '%'.$options['search'].'%');
        }

        return $qb;
    }
    
    public function getNbParisQueryBuilder($options=null)
    {
    	
        $qb = $this->findByQueryBuilder($options);            
        return $qb->select('COUNT(DISTINCT p.id) AS nbParis');
         
    }
    
    public function addLimit($qb, $limit=array())
    {
        if (isset($limit['start'])) {
            $qb->setFirstResult($limit['start']);
        }
        if (isset($limit['length'])) {
            $qb->setMaxResults($limit['length']);
        }

        return $qb;
    }

    public function addSortings($qb, $sortings=array())
    {
        if (count($sortings) == 0) {
            $qb->orderBy('p.id', 'ASC');
        } else {
            foreach ($sortings as $sortingCcolumn => $sortingDirection) {
                $qb->addOrderBy($sortingCcolumn, $sortingDirection);
            }
        }

        return $qb;
    }
    
}
