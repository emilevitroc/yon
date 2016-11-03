<?php

namespace Yon\Bundle\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yon\Bundle\UserBundle\Entity\ApiUserprofile;
use Yon\Bundle\UserBundle\Entity\AuthUser;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;
use Yon\Bundle\ParisBundle\Entity\ApiBet;


class ApiUserprofileRepository extends EntityRepository
{
    public function findByQueryBuilder($options=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->join('u.user', 'uh');
//        $qb->andWhere('s.deleted = 0');

//        if (isset($options['customerUid'])) {
//            if(($options['customerUid'] == '##')) {
//                $qb
//                ->andWhere('p.customer_uid = 0');
//            } else {
//                $qb
//                ->andWhere('p.customer_uid = :customerUid')
//                ->setParameter('customerUid', $options['customerUid']);
//            }
//            
//        }

        if (isset($options['search'])) {
            $qb
                ->andWhere('u.id = :searchId OR uh.username like :searchName OR u.phoneNumber like :searchName')
                ->setParameter('searchId', $options['search'])
                ->setParameter('searchName', '%'.$options['search'].'%');
        }

        return $qb;
    }
    
    public function getNbUsersQueryBuilder($options=null)
    {
    	
        $qb = $this->findByQueryBuilder($options);            
        return $qb->select('COUNT(DISTINCT u.id) AS nbUsers');
         
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
            $qb->orderBy('u.id', 'ASC');
        } else {
            foreach ($sortings as $sortingCcolumn => $sortingDirection) {
                $qb->addOrderBy($sortingCcolumn, $sortingDirection);
            }
        }

        return $qb;
    }
    
    public function findUserIdsByQueryBuilder($options=null){
        
        /*if(!empty($options['nbYon'])){
            $qb = $this->createQueryBuilder('u')->select('u');
        }
        else
        { */   
        
            if(!empty($options['nbYon']) && !isset($options['nbChallenge']) && !isset($options['nbPlayed'])){
                
                $qb = $this->createQueryBuilder('u')->select('u');
            }
            else 
            {
               
                    $qb = $this->createQueryBuilder('u')->select('u');
                    $qb->leftJoin('u.user', 'uh');

                    $qb->leftJoin('uh.challenge', 'uhc');

                    $qb->leftJoin('uh.bets', 'uhb');
                
            }
        //}
        
        
        if(isset($options['nbYonfrom']) || isset($options['nbYonto']))
        {
           // die();
            if((isset($options['nbYonfrom']) || $options['nbYonfrom'] != "") && (empty($options['nbYonto']))){
                $qb ->andwhere('u.balance >= :balance1')
                    ->setParameter('balance1', $options['nbYonfrom']);
            }else if((isset($options['nbYonto']) || $options['nbYonto'] != "") && (empty($options['nbYonfrom'])))
            {
                $qb ->andwhere('u.balance <= :balance2')
                    ->setParameter('balance2', $options['nbYonto']);
            }
            else{
                $qb ->andwhere('u.balance between :balance1 and :balance2')
                    ->setParameter('balance1', $options['nbYonfrom'])
                    ->setParameter('balance2', $options['nbYonto']);
            }
        }
        
        if(isset($options['nbch']) || isset($options['d_nbch1']) || isset($options['d_nbch2']) )
        {
            if((isset($options['d_nbch1'])) && (empty($options['d_nbch2'])) ){
                $qb ->andwhere("uhc.creation >= :date1");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhc) >= :nb')
                   ->setParameter('date1', $options['d_nbch1'])
                   ->setParameter('nb', $options['nbch']);
            }
            
            else if((isset($options['d_nbch2'])) && (empty($options['d_nbch1'])))
            {
                $qb ->andwhere("uhc.creation <= :date2");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhc) >= :nb')
                   ->setParameter('date2', $options['d_nbch2'])
                   ->setParameter('nb', $options['nbch']);
            }
            else{
                $qb ->andwhere("uhc.creation between :date1 and :date2");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhc) >= :nb')
                   ->setParameter('date1', $options['d_nbch1'])
                   ->setParameter('date2', $options['d_nbch2'])
                   ->setParameter('nb', $options['nbch']);
            }
        }
        
        
        
        
        
        if(isset($options['nbP']) || isset($options['d_nbpl1']) || isset($options['d_nbpl2']) )
        {
            if((isset($options['d_nbpl1'])) && (empty($options['d_nbpl2'])) ){
                $qb ->andwhere("uhb.creation >= :date1");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhb) >= :nb')
                   ->setParameter('date1', $options['d_nbpl1'])
                   ->setParameter('nb', $options['nbP']);
            }
            
            else if((isset($options['d_nbpl2'])) && (empty($options['d_nbpl1'])))
            {
                $qb ->andwhere("uhb.creation <= :date2");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhc) >= :nb')
                   ->setParameter('date2', $options['d_nbpl2'])
                   ->setParameter('nb', $options['nbP']);
            }
            else
            {
                $qb ->andwhere("uhb.creation between :date1 and :date2");
                $qb->groupBy('u.id'); // or use an appropriate field
                $qb->having('COUNT(uhb) >= :nb')
                   ->setParameter('date1', $options['d_nbpl1'])
                   ->setParameter('date2', $options['d_nbpl2'])
                   ->setParameter('nb', $options['nbP']);
            }
        }
        
        
        
//        else{
//            $qb->setFirstResult(0)->setMaxResults(0);
//        }      
//        var_dump($options);        
//        print_r($qb->getQuery()->getSql()); 
//        die();      
//        
//        foreach ($qb->getQuery()->getResult() as $resu){
//            var_dump($resu->getUser());
//            
//        }
        
        return $qb;
    }
    
}
