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
            //echo $options['status'];
            
            if( $options['status'] === 0){
                 $qb
                ->andWhere('p.status = :status')
                ->setParameter('status', $options['status']);
            }
        }
        
        if (isset($options['coucoursId']) && $options['coucoursId'] != "") {
            $qb
                ->andWhere('pc.id = :coucoursId')
                ->setParameter('coucoursId', $options['coucoursId']);
        }

        if (isset($options['search'])) {
            $qb
                ->andWhere('p.id = :searchId OR pu.username like :searchName OR pu.firstName like :searchName OR p.title like :searchName OR ph.tag like :searchName' )
                ->setParameter('searchId', $options['search'])
                ->setParameter('searchName', '%'.$options['search'].'%');
        }
        
        //moderation
        if (isset($options['isModerate']) && $options['isModerate'] == 1) {
            $qb->andWhere('p.moderateAt IS NULL');
            $qb->andWhere('p.endDate > current_timestamp()');
        }
        if (isset($options['resetModerate']) && $options['resetModerate'] == 1) {
            $date = new \DateTime();
            $date->modify('-15 minute');
            $qb->andWhere('p.moderateAt IS NOT NULL');
            $qb->andWhere('p.moderateAt  <= :date');
            $qb->setParameter(':date', $date);
        }
        if (isset($options['state']) && $options['state'] != "") {
            $qb
                ->andWhere('p.state = :state')
                ->setParameter('state', $options['state']);
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
