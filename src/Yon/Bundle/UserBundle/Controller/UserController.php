<?php

namespace Yon\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $post_data = array(
            'token' => $session->get ( 'yon_token')
        );
        
        $userListUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('users') ;
        
        $curlService = $this->get('yon_user.data');
        
        $result = $curlService->curlGet($userListUrl, $post_data);
        
        //traitement des redirection si OK ou pas
        $response = json_decode($result);
        
//        var_dump($response);die;
        
        return $this->render('YonUserBundle:User:index.html.twig', array('utilisateurs' => $response));
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
