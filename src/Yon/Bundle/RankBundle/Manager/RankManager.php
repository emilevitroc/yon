<?php

namespace Yon\Bundle\RankBundle\Manager;

use Doctrine\ORM\EntityManager;

class RankManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRepository()
    {
        return $this->em->getRepository('YonParisBundle:ApiContestuserranking');
    }
    
    public function load($id)
    {
    	return $this
    	->getRepository()
    	->findOneBy(array('id' => $id));
    }
    
   public function getUsersBy( $options, $filters=array(), $sortings=array())
    {
        $query = $this->getRepository()->findByQueryBuilder($options);
        $query = $this->getRepository()->addSortings($query, $sortings);

        if ($filters != null) {
            $query = $this->getRepository()->addLimit($query, $filters);
        }
        
        return $query->getQuery();
    }
    
    public function getNbUsersBy($options=array(), $filters=array())
    {    	
        $query = $this->getRepository()->getNbUsersQueryBuilder($options);
        return $query->getQuery()->getSingleResult();
    }
    
    public function getAllContest()
    {    	
        $query = $this->getRepository()->getContestQueryBuilder();
        return $query;
    }
    
}