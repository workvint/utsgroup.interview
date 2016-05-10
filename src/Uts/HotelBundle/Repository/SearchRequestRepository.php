<?php

namespace Uts\HotelBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Uts\HotelBundle\Entity\SearchRequest;

class SearchRequestRepository extends EntityRepository
{
    public function findCompleteBy(SearchRequest $searchRequest)
    {
        return $this->findOneBy(
            array(
                'city'      => $searchRequest->getCity(),
                'checkIn'   => $searchRequest->getCheckIn(),
                'checkOut'  => $searchRequest->getCheckOut(),
                'adults'    => $searchRequest->getAdults(),
                'status'    => $searchRequest::STATUS_COMPLETE,
            ),
            array(
                'createdAt' => 'desc',
            )
        );
    }
}