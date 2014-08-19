<?php

namespace Uts\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Uts\HotelBundle\Entity\SearchRequest;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $objSearchForm = $this->createForm('uts_hotel_search_request', new SearchRequest());
        $objSearchForm->handleRequest($request);
        return $this->render('UtsHotelBundle:Default:index.html.twig', array('searchForm' => $objSearchForm->createView()));
    }

    public function processAction(Request $request)
    {
        $objSearchForm = $this->createForm('uts_hotel_search_request', new SearchRequest());
        $objSearchForm->submit($request->query->get($objSearchForm->getName()), false);
        if($objSearchForm->isValid()){
            $objSearchRequest = $objSearchForm->getData();
            /* @var $objSearchRequest SearchRequest */
            $em = $this->getDoctrine()->getManagerForClass('UtsHotelBundle:SearchRequest');
            $em->persist($objSearchRequest);
            $em->flush();

            try{
                $objSearcher = $this->get('uts_hotel.searcher');
                $results = $objSearcher->search($objSearchRequest);
                foreach($results as $objResult){
                    $em->persist($objResult);
                }
                $objSearchRequest->markAsComplete();
                $em->flush();
            }catch (\Exception $err){
                $objSearchRequest->markAsError();
                $em->flush();
                throw $err;
            }

            return new RedirectResponse(
                $this->get('router')
                    ->generate('uts_hotel_search_results', array('searchId' => $objSearchRequest->getId()))
            );
        }else{
            return $this->render('UtsHotelBundle:Default:index.html.twig', array('searchForm' => $objSearchForm->createView()));
        }
    }

    public function resultsAction($searchId, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $objSearchRequest = $em->find('UtsHotelBundle:SearchRequest', $searchId);
        if(!$objSearchRequest){
            $this->createNotFoundException();
        }

        $objSearchForm = $this->createForm('uts_hotel_search_request', $objSearchRequest);
        $templateVars = array(
            'searchForm' => $objSearchForm->createView(),
            'request' => $objSearchRequest
        );
        if($objSearchRequest->isComplete() || $objSearchRequest->isOld()){
            $repository = $em->getRepository('UtsHotelBundle:SearchResult');
            $query = $repository->createQueryForPagination($searchId);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate($query, $page, 50);
            $templateVars['pagination'] = $pagination;
        }
        return $this->render('UtsHotelBundle:Default:results.html.twig', $templateVars);
    }
}
