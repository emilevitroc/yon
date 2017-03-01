<?php

namespace Yon\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yon\Bundle\UserBundle\Entity\ApiUserprofile;
use Yon\Bundle\PrivilegeBundle\Entity\ApiFeature;

class UserController extends Controller
{
    
    public function newAction(Request $request)
    {
        
        $session = $request->getSession ();
        //var_dump($session->get ( 'privileges'));die;
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $post_data = array(
            'token' => $session->get ( 'yon_token')
        );
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
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
            if($data['type'] == 1){
                $data['is_staff'] = true;
            }
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
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
        $tBreadcrumbs = array();
        $oBreadcrumb = new \stdClass();
        $oBreadcrumb->label= 'Utilisateur';
        $oBreadcrumb->href = '';
        $tBreadcrumbs[] = $oBreadcrumb;
        
//        //get User by WS
        $userUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users').'/'.$utilisateur->getId() ;
//        
        $curlService = $this->get('yon_user.data');
//        
//        $result = $curlService->curlGet($userUrl, $post_data);
//        $utilisateur = json_decode($result);
        
        if ($request->isMethod("POST")) {
            $data = $request->request->all();
            if($data['type'] == 1){
                $data['is_staff'] = true;
            }
            
            //MAJ user
            $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
            $result = $curlService->curlPatch($userUrl, $data, $customerHeader);
            $response = json_decode($result);
            
            if(isset($response->message)){
                $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
            } else {
                $upUserProfile = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:ApiUserprofile')->find($utilisateur->getId());
                $upUserProfile->setBalance($data['balance']);
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $upUserProfile->setToBelongToUser($data['to_belong_to_user']);
                }
                $upUserProfile->setStar($data['star']);
                $upUserProfile->setType($data['type']);
                $em = $this->getDoctrine()->getManager();
                $em->persist($upUserProfile);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', sprintf('Utilisateur a été bien modifié!.'));
            }
            
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
        $ApiUserprofile = new ApiUserprofile();
        $typeUser = $ApiUserprofile::$USER_TYPE;
//        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
//            throw new AccessDeniedHttpException ();
//        }
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
        return $this->render('YonUserBundle:User:index.html.twig', array(
            'typeUser' => $typeUser
            )
        );
    }
    
    public function userListAjaxAction(Request $request)
    {
        $session    = $request->getSession ();
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
        $rankdeb         = $request->get('rankdeb', null);
        $rankfin         = $request->get('rankfin', null);
        $nbparisdeb      = $request->get('nbparisdeb', null);
        $nbparisfin      = $request->get('nbparisfin', null);
        $nbfollowersdeb  = $request->get('nbfollowersdeb', null);
        $nbfollowersfin  = $request->get('nbfollowersfin', null);
        $nbfolloweddeb   = $request->get('nbfolloweddeb', null);
        $nbfollowedfin   = $request->get('nbfollowedfin', null);
        $pointsdeb       = $request->get('pointsdeb', null);
        $pointsfin       = $request->get('pointsfin', null);
        $valTypeUser       = $request->get('valTypeUser', null);
        

        $options = array(
            'search' => $request->query->get('sSearch')
        );
        
        $options['rankdeb']             = $rankdeb;
        $session->set('rankdeb', $rankdeb);
        $LastUserAjaxParams['rankdeb']  = $rankdeb;
        
        $options['rankfin']             = $rankfin;
        $session->set('rankfin', $rankfin);
        $LastUserAjaxParams['rankfin']  = $rankfin;
        
        
        $options['nbparisdeb']            = $nbparisdeb;
        $session->set('nbparisdeb', $nbparisdeb);
        $LastUserAjaxParams['nbparisdeb'] = $nbparisdeb;
        
        $options['nbparisfin']            = $nbparisfin;
        $session->set('nbparisfin', $nbparisfin);
        $LastUserAjaxParams['nbparisfin'] = $nbparisfin;
        
        
        $options['nbfollowersdeb']            = $nbfollowersdeb;
        $session->set('nbfollowersdeb', $nbfollowersdeb);
        $LastUserAjaxParams['nbfollowersdeb'] = $nbfollowersdeb;
        
        $options['nbfollowersfin']            = $nbfollowersfin;
        $session->set('nbfollowersfin', $nbfollowersfin);
        $LastUserAjaxParams['nbfollowersfin'] = $nbfollowersfin;
        
        
        $options['nbfolloweddeb']            = $nbfolloweddeb;
        $session->set('nbfolloweddeb', $nbfolloweddeb);
        $LastUserAjaxParams['nbfolloweddeb'] = $nbfolloweddeb;
        
        $options['nbfollowedfin']            = $nbfollowedfin;
        $session->set('nbfollowedfin', $nbfollowedfin);
        $LastUserAjaxParams['nbfollowedfin'] = $nbfollowedfin;
        
        
        $options['pointsdeb']            = $pointsdeb;
        $session->set('pointsdeb', $pointsdeb);
        $LastUserAjaxParams['pointsdeb'] = $pointsdeb;
        
        $options['pointsfin']            = $pointsfin;
        $session->set('pointsfin', $pointsfin);
        $LastUserAjaxParams['pointsfin'] = $pointsfin;
        
        
        $options['valTypeUser']            = $valTypeUser;
        $session->set('valTypeUser', $valTypeUser);
        $LastUserAjaxParams['valTypeUser'] = $valTypeUser;
        
        $userListAjaxUrl = $this->generateUrl('yon_user_list_ajax', $LastUserAjaxParams , true);
        $session->set('userListAjaxUrl', str_replace('amp;', '', $userListAjaxUrl));
        
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
            if($entity->getUser()->getFirstName() !== ""){
                $name->firstName = $entity->getUser()->getFirstName();
            }else{
                $name->firstName = 'Pas de nom ( ' . $entity->getPhoneNumber().' )';
            }
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
                $utilisateur = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:ApiUserprofile')->find($response->user->id);
		//$session->set ( 'user_profil_id', $response->id );
		$session->set ( 'user_type', $utilisateur->getType() );
                
                // get list privilege
                $apiFeatures = array();
                switch ($utilisateur->getType()) {
                    case 2://Admin Interne
                        $apiFeatures = $this->getDoctrine()->getManager()->getRepository('YonPrivilegeBundle:ApiFeature')->findBy(array('isAdminIntern' => 1));
                    break;
                    case 3://Admin Externe
                        $apiFeatures = $this->getDoctrine()->getManager()->getRepository('YonPrivilegeBundle:ApiFeature')->findBy(array('isAdminExtern' => 1));
                    break;
                    case 4://Partenaire Commercial
                        $apiFeatures = $this->getDoctrine()->getManager()->getRepository('YonPrivilegeBundle:ApiFeature')->findBy(array('isPartenaireCommercial' => 1));
                    break;

                }
                
                $privileges = '';
                if($utilisateur->getType() == 1 ){ //superadmi
                    $privileges = 'all';
                } else {
                    $tPrivileges = array();
                    foreach ($apiFeatures as $apiFeature) {
                        $tPrivileges = array_merge($tPrivileges, ApiFeature::$FEATURES[$apiFeature->getId()]);
                    }
                    $privileges .= implode(',', $tPrivileges);
                }
                $session->set ( 'privileges',  $privileges );
                
            }
            
            if($utilisateur->getType() == 4 || $utilisateur->getType() == 3)
            {
                $response->redirect_url =  $this->generateUrl('apichallenge_index');
            }else{
                $response->redirect_url =  $this->generateUrl('yon_user_homepage');
            }
            
           
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
