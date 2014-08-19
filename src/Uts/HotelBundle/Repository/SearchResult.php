<?php

namespace Uts\HotelBundle\Repository;

use \Doctrine\ORM\EntityRepository;

class SearchResult extends EntityRepository
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
        ;
        return $qb->getQuery();
    }
}