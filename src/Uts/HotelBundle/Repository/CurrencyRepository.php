<?php

namespace Uts\HotelBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CurrencyRepository extends EntityRepository
{
    public function findAllArray()
    {
        $qb = $this->createQueryBuilder('self');
        $currencies = $qb->getQuery()->iterate();
        
        $result = array();       
        foreach($currencies as $currency) {
            $currency = $currency[0];
            $result[$currency->getCode()] = $currency->getRate();
        }
        
        return $result;
    }
}