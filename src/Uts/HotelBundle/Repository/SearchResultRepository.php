<?php

namespace Uts\HotelBundle\Repository;

use \Doctrine\ORM\EntityRepository;

class SearchResultRepository extends EntityRepository
{
    public function createQueryForPagination($requestId)
    {
        $qb = $this->createQueryBuilder('self');
        $qb
            ->join('self.hotel', 'hotel')
            ->leftJoin('self.meal', 'meal')
            ->addSelect('hotel')
            ->addSelect('meal')
            ->andWhere('self.request = ?0')
            ->setParameters(array($requestId))
            ->addOrderBy('hotel.name', 'asc')
            ->addOrderBy('hotel.id', 'asc')
            ->addOrderBy('self.price', 'asc')
        ;
        
        /**
         * @todo сортировка по цене для разных вылют (нет в задании): 
         * если для одного отеля есть варианты с разными валютами 
         */
        
        return $qb->getQuery();
    }
    
    public function getHotelCount($requestId)
    {
        $qb = $this->createQueryBuilder('self');
        $qb
            ->select($qb->expr()->countDistinct('self.hotel'))
            ->andWhere('self.request = ?0')
            ->setParameters(array($requestId))
        ;
        
        return $qb->getQuery()->getSingleScalarResult();
    }
}