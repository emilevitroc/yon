<?php

namespace Yon\Bundle\RankBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RankController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $em = $this->getDoctrine()->getManager();
        $apiContests = $em->getRepository('YonParisBundle:ApiContest')->findBy(array(), array('name' => 'ASC'));;
        
        return $this->render('YonRankBundle:Rank:index.html.twig', array(
            'apiContests' => $apiContests,
        ));
    }
    public function generalAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        return $this->render('YonRankBundle:Rank:general.html.twig');
    }
    
    public function rankListAjaxAction(Request $request)
    {
        
        $filters  = $this->getFilters($request);
        $sortings = $this->getSortings($request, array(
            'ur.rank',
            'u.id',
            'u.displayUsername',
            'u.phoneNumber',
//            'u.challengesCount',
            'u.followersCount',
            'u.followingsCount',
            'ur.rank',
        ));
//        $constestId = $request->query->get('contestId');
//        echo $request->query->get('sSearch');
        
        $options = array(
            'search' => $request->query->get('sSearch'),
            'contestId' => $request->query->get('contestId'),
        );
        
//        if($constestId){
//            $options['contestId'] = $constestId;
//        }
        $nbUsers         = $this->get('yon_rank.rank_manager')->getNbUsersBy($options);
        $usersResult   = $this->get('yon_rank.rank_manager')->getUsersBy($options, $filters, $sortings)->getResult();

        $content = $this->getDataJson(
            $request,
            $nbUsers['nbUsers'],
            $nbUsers['nbUsers'],
            $usersResult,
            'YonRankBundle:Rank:rankListAjax.json.twig'
        );

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    private function getDataJson($request, $nbTotal, $nbDisplayed, $values, $template)
    {
        $data['sEcho']                = $request->query->get('sEcho');
        $data['iTotalRecords']        = (int) $nbTotal;
        $data['iTotalDisplayRecords'] = (int) $nbDisplayed;

        return $this->renderView($template, array('data' => $data, 'values' => $values));
    }
    
    private function getFilters($request) {
        $filters = array();
        $start = $request->query->get('iDisplayStart');
        $length = $request->query->get('iDisplayLength');

        if (isset($start)) {
            $filters['start'] = (int) $start;
        }
        if (isset($length)) {
            $filters['length'] = (int) $length;
        }

        return $filters;
    }

    private function getSortings($request, $columns) {
        $sortings = array();

        foreach ($columns as $k => $v) {
            $isSortCol = $request->query->get('iSortCol_' . $k);
            if (isset($isSortCol) && $columns[$isSortCol]) {
                $orderColumn = $columns[$isSortCol];
                $sSortDir = $request->query->get('sSortDir_' . $k);
                if (isset($sSortDir) && $sSortDir == 'asc') {
                    $orderDirection = 'ASC';
                } else {
                    $orderDirection = 'DESC';
                }

                $sortings[$orderColumn] = $orderDirection;
            }
        }

        return $sortings;
    }
}
