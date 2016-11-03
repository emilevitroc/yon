<?php

namespace Yon\Bundle\ParisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yon\Bundle\ParisBundle\Entity\ApiContestuserranking;

class ApiContestuserrankingRepository extends EntityRepository
{
    public function findByQueryBuilder($options=null)
    {
        $qb = $this->createQueryBuilder('ur')
            ->select('ur')
            ->join('ur.user', 'uh')
            ->join('uh.user', 'u')
            ->andWhere('ur.rank != 0');
    
        if (isset($options['contestId'])) {
            $qb
            ->andWhere('ur.contest = :contestId')
            ->setParameter('contestId', $options['contestId']);
        } else {
            //die('fsdfsd');
        }

        if (isset($options['search'])) {
            $qb
                ->andWhere('u.id = :searchId OR uh.username like :searchName OR u.phoneNumber like :searchName OR u.displayUsername like :searchName')
                ->setParameter('searchId', $options['search'])
                ->setParameter('searchName', '%'.$options['search'].'%');
        }

        return $qb;
    }
    
    public function getNbUsersQueryBuilder($options=null)
    {
    	
        $qb = $this->findByQueryBuilder($options);            
        return $qb->select('COUNT(DISTINCT ur.id) AS nbUsers');
         
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
            $qb->orderBy('ur.id', 'ASC');
        } else {
            foreach ($sortings as $sortingCcolumn => $sortingDirection) {
                $qb->addOrderBy($sortingCcolumn, $sortingDirection);
            }
        }

        return $qb;
    }
    
}
