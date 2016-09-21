<?php

namespace Yon\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
//        var_dump($session->get ( 'yon_token'));
//        $post_data = array(
//            'token' => $session->get ( 'yon_token')
//        );
        
        //$userListUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users') ;
        
        //$curlService = $this->get('yon_user.data');
        
        //$result = $curlService->curlGet($userListUrl, $post_data);
        //$response = json_decode($result);
        
//        var_dump($response);die;
        
//        return $this->render('YonUserBundle:User:index.html.twig', array('utilisateurs' => $response));
        return $this->render('YonUserBundle:User:index.html.twig');
    }
    
    public function userListAjaxAction(Request $request)
    {
        
        $filters  = $this->getFilters($request);
        $sortings = $this->getSortings($request, array(
            'u.id',
            'uh.username',
            'u.phoneNumber',
            'u.rank',
            'u.challengesCount',
            'u.followersCount',
            'u.followingsCount',
        ));
//        $customerUid = $request->get('cust');
        
        
        $options = array(
            'search' => $request->query->get('sSearch')
        );
        
//        if($customerUid){
//            $options['customerUid'] = $customerUid;
//        }
        
        //$nbUsers = count($this->get('yon_user.user_manager')->getUsersBy($options)->getResult());
        $nbUsers         = $this->get('yon_user.user_manager')->getNbUsersBy($options);
        $usersResult   = $this->get('yon_user.user_manager')->getUsersBy($options, $filters, $sortings)->getResult();

        $content = $this->getDataJson(
            $request,
            $nbUsers['nbUsers'],
            $nbUsers['nbUsers'],
            $usersResult,
            'YonUserBundle:User:userListAjax.json.twig'
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
    
    public function loginAction(Request $request)
    {
        $session = $request->getSession ();
        if($session->get ( 'aci_token')){
            $url = $this->container->get('router')->generate('aci_dashboard_index');
            $response = new RedirectResponse($url);
            return $response;
        }
        return $this->render('YonUserBundle:User:login.html.twig');
    }
    
    public function loginAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        $post_data = $request->request->all();
        
        
        //url-ify the data for the POST
        $fields_string = '';
        foreach($post_data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string,'& ');
        
//        var_dump($post_data);die;
        $connectUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('authentification') ;
        
        $curlService = $this->get('yon_user.data');
        
        $result = $curlService->curlPost($connectUrl, $post_data);
        
        //traitement des redirection si OK ou pas
        $response = json_decode($result);
        
        if(isset($response->user)){
            if($response->token && $response->token != '') {
                $session = $request->getSession ();
		$session->set ( 'yon_token', $response->token );
		$session->set ( 'user_infos', $response->user );
            }
            $response->redirect_url =  $this->generateUrl('yon_user_homepage');
        }
        
        return new JsonResponse($response);
    }
    
    public function logoutAction(Request $request)
    {
        $session = $request->getSession ();
        $session->clear();
        
        $url = $this->container->get('router')->generate('yon_user_login');
        $response = new RedirectResponse($url);
        
        return $response;
    }
    
}
