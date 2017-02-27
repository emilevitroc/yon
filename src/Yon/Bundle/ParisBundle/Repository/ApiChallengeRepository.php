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
        
        if ( (isset($options['startDate']) && $options['startDate'] !== "") ) {
            
                $qb
                ->andWhere('p.startDate  >=  :startDate')
                ->setParameter('startDate', $options['startDate']);
            
        }
        if ((isset($options['endDate']) && $options['endDate'] !== "")) {
            
                $qb
                ->andWhere('p.startDate  <= :endDate ')
                ->setParameter('endDate', $options['endDate']);
            
        }
        
        if ((isset($options['nbpartdeb']) && $options['nbpartdeb'] !== "")) {
            
                $qb
                ->andWhere('p.betsCount  >=  :nbpartdeb')
                ->setParameter('nbpartdeb', $options['nbpartdeb']);
            
        }
        
        if ((isset($options['hashtag']) && $options['hashtag'] !== "")) {
            
                $qb
                ->andWhere('ph.tag like :hashtag')
                ->setParameter('hashtag', '%'.$options['hashtag'].'%');
            
        }
        
        if ((isset($options['nbpartfin']) && $options['nbpartfin'] !== "")) {
            
                $qb
                ->andWhere('p.betsCount  <= :nbpartfin ')
                ->setParameter('nbpartfin', $options['nbpartfin']);
            
        }
        
        if (isset($options['coucoursId']) && $options['coucoursId'] != "") {
            $qb
                ->andWhere('pc.id = :coucoursId')
                ->setParameter('coucoursId', $options['coucoursId']);
        }
        
        if (isset($options['userId']) && $options['userId'] > 0) {
            $qb
                ->andWhere('pu.id = :userId')
                ->setParameter('userId', $options['userId']);
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
    
//    public function findUserParisByQueryBuilder($options=null){
//        $qb =  $this->createQueryBuilder('p')
//                    ->select('p')
//                    ->where("p.creation BETWEEN  :date1 AND :date2")
//                    ->setParameter('date1', '2015-09-11 18:03:34')
//                    ->setParameter('date1', '2015-09-11 23:49:38');
//        return $qb;
//    }
    
}
