<?php

namespace Yon\Bundle\ParisBundle\Manager;

use Doctrine\ORM\EntityManager;

class ParisManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRepository()
    {
        return $this->em->getRepository('YonParisBundle:ApiChallenge');
    }
    
    public function load($id)
    {
    	return $this
    	->getRepository()
    	->findOneBy(array('id' => $id));
    }
    
   public function getParisBy( $options, $filters=array(), $sortings=array())
    {
        $query = $this->getRepository()->findByQueryBuilder($options);
        $query = $this->getRepository()->addSortings($query, $sortings);

        if ($filters != null) {
            $query = $this->getRepository()->addLimit($query, $filters);
        }
        
        return $query->getQuery();
    }
    
    public function getNbParisBy($options=array(), $filters=array())
    {    	
        $query = $this->getRepository()->getNbParisQueryBuilder($options);
        return $query->getQuery()->getSingleResult();
    }
    
    public function getContestByDate($options=array())
    {    	
        $query = $this->getRepository()->getContestByDateQueryBuilder($options);
        return $query;
    }
    
}