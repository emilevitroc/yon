<?php

namespace Yon\Bundle\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yon\Bundle\UserBundle\Entity\ApiUserprofile;

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
        $qb = $this->createQueryBuilder('u')->select('u');
        
        
        if(isset($options['nbYonfrom']) || isset($options['nbYonto']))
        {
            
            if((isset($options['nbYonfrom']) || $options['nbYonfrom'] != "" || $options['nbYonfrom'] != 0) && ($options['nbYonto'] == "" || $options['nbYonto'] == 0)){
                $qb ->andwhere('u.balance = :balance1')
                    ->setParameter('balance1', $options['nbYonfrom']);
            }else if((isset($options['nbYonto']) || $options['nbYonto'] != "" || $options['nbYonto'] != 0) && ($options['nbYonfrom'] == "" || $options['nbYonfrom'] == 0))
            {
                $qb ->andwhere('u.balance <= :balance2')
                    ->setParameter('balance2', $options['nbYonto']);
            }
            else{
                $qb ->andwhere('u.balance between :balance1 and :balance2')
                    ->setParameter('balance1', $options['nbYonfrom'])
                    ->setParameter('balance2', $options['nbYonto']);
            }
        }else{
            $qb->setMaxResults(0)->setFirstResult(0);
        }
        return $qb;
    }
    
}
