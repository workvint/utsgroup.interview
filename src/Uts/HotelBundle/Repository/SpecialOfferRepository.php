<?php

namespace Uts\HotelBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Uts\HotelBundle\Entity\SearchRequest;

class SpecialOfferRepository extends EntityRepository
{
    public function findActiveBy(SearchRequest $searchRequest)
    {
        $qb = $this->createQueryBuilder('self');
        $offers = $qb
            ->where('self.isActive=:active')
            ->andWhere('self.country=:country')
            ->andWhere('self.city IS NULL OR self.city=:city')
            ->setParameters(array(
                'active'    => 1,
                'country'   => $searchRequest->getCity()->getCountry(),
                'city'      => $searchRequest->getCity(),
            ))
        ->getQuery()->iterate();
        
        $result = array(
            'hotel'     => array(),
            'city'      => array(),
            'country'   => array(),
        );
        
        foreach($offers as $offer) {
            $offer = $offer[0];
            
            $type = $offer->getDiscountType();
            $value = $offer->getDiscountValue();
            
            $key1 = 'country';
            $key2 = $offer->getCountry()->getId();
            
            if ($offer->getHotel()) {
                $key1 = 'hotel';
                $key2 = $offer->getHotel()->getId();
            } elseif ($offer->getCity()) {
                $key1 = 'city';
                $key2 = $offer->getCity()->getId();
            }
            
            if (!key_exists($key2, $result[$key1])) {
                $result[$key1][$key2] = array();
            }
            
            if (!key_exists($type, $result[$key1][$key2]) || 
                $result[$key1][$key2][$type]['value'] < $value) 
            {
                $result[$key1][$key2][$type] = array(
                    'id'    => $offer->getId(),
                    'name'  => $offer->getName(),
                    'value' => $value,
                );
            }
        }
        
        return $result;
    }
}