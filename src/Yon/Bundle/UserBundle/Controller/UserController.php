<?php

namespace Yon\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yon\Bundle\UserBundle\Entity\ApiUserprofile;

class UserController extends Controller
{
    
    public function newAction(Request $request)
    {
        
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $post_data = array(
            'token' => $session->get ( 'yon_token')
        );
        
        $tBreadcrumbs = array();
        $oBreadcrumb = new \stdClass();
        $oBreadcrumb->label= 'Utilisateur';
        $oBreadcrumb->href = '';
        $tBreadcrumbs[] = $oBreadcrumb;
        
//        //get User by WS
//        $userUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users').'/'.$id ;
//        
//        $curlService = $this->get('yon_user.data');
//        
//        $result = $curlService->curlGet($userUrl, $post_data);
//        $utilisateur = json_decode($result);
        
        if ($request->isMethod("POST")) {
            $data = $request->request->all();
            $connectUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users') ;
        
            $curlService = $this->get('yon_user.data');
        
            $result = $curlService->curlPost($connectUrl, $data);
        
            
            $response = json_decode($result);
            if($response && isset($response->id) && $response->id > 0){
                $userId = $response->id;
                $utilisateur = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:ApiUserprofile')->find($userId);
                
                $utilisateur->setLocale($data['locale']);
                $utilisateur->setType($data['type']);
                $utilisateur->setStar($data['star']);
                $utilisateur->setBalance($data['balance']);
                
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $utilisateur->setToBelongToUser($data['to_belong_to_user']);
                }
                
                $utilisateur->getUser()->setFirstName($data['name']);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
            }
            
            $this->get('session')->getFlashBag()->add('success', sprintf('un utilisateur a été enregistré!.'));
            return $this->redirectToRoute('yon_user_homepage');
        }

        return $this->render('YonUserBundle:User:new.html.twig', array(
            'tBreadcrumbs' => $tBreadcrumbs,
            'tType' => \Yon\Bundle\UserBundle\Entity\ApiUserprofile::$USER_TYPE
        ));
    }
    
    public function editAction(Request $request, ApiUserprofile $utilisateur)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $post_data = array(
            'token' => $session->get ( 'yon_token')
        );
        $tBreadcrumbs = array();
        $oBreadcrumb = new \stdClass();
        $oBreadcrumb->label= 'Utilisateur';
        $oBreadcrumb->href = '';
        $tBreadcrumbs[] = $oBreadcrumb;
        
//        //get User by WS
//        $userUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users').'/'.$id ;
//        
//        $curlService = $this->get('yon_user.data');
//        
//        $result = $curlService->curlGet($userUrl, $post_data);
//        $utilisateur = json_decode($result);
        
        if ($request->isMethod("POST")) {
            $data = $request->request->all();
            $utilisateur->getUser()->setUsername($data['username']);
            $utilisateur->setLocale($data['locale']);
            $utilisateur->setPhoneNumber(str_replace(' ','',$data['phone_number']));
            $utilisateur->setBalance($data['balance']);
            if( (int)$data['to_belong_to_user'] > 0 ){
                $utilisateur->setToBelongToUser($data['to_belong_to_user']);
            }
            $utilisateur->setStar($data['star']);
            $utilisateur->setType($data['type']);
            $utilisateur->setDisplayUsername($data['name']);
            $utilisateur->getUser()->setFirstName($data['name']);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', sprintf('Utilisateur a été bien modifié!.'));
            return $this->redirectToRoute('yon_user_edit', array('id' => $utilisateur->getId()));
        }

        return $this->render('YonUserBundle:User:edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'tBreadcrumbs' => $tBreadcrumbs,
            'tType' => \Yon\Bundle\UserBundle\Entity\ApiUserprofile::$USER_TYPE
        ));
    }
    
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
            'uh.firstName',
            'u.phoneNumber',
            'u.rank',
            'u.challengesCount',
            'u.followersCount',
            'u.followingsCount',
            'u.balance',
            'u.type',
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
    
    public function userAutocompleteAction(Request $request) {

        $names = array();
        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('YonUserBundle:ApiUserprofile')->createQueryBuilder('u')->join('u.user', 'uh')
                ->where('u.id = :name OR uh.username like :name OR u.phoneNumber like :name')
                ->setParameter('name', '%'.$term . '%')->setFirstResult(0)->setMaxResults(10);
        
        
        $users = $users->getQuery();
        $users = $users->getResult();
        
        foreach ($users as $entity) {
            
            $name = new \stdClass();
            $name->id = $entity->getId();
            $name->authid = $entity->getUser()->getId();
//            $name->value = $entity->getUser()->getUsername() . ' (' . $entity->getPhoneNumber().')';
            $name->value = $entity->getId().'-'.$entity->getUser()->getUsername().'-'.$entity->getDisplayUsername().' (' . $entity->getPhoneNumber().')';
            
//            $name->label = $entity->getUser()->getUsername();
            $name->label = $entity->getId().'-'.$entity->getUser()->getUsername().'-'.$entity->getDisplayUsername().' (' . $entity->getPhoneNumber().')';
            $names[] = $name;
            
        }

        $response = new JsonResponse();
        $response->setData($names);

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
		//$session->set ( 'user_profil_id', $response->id );
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
