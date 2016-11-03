<?php

namespace Yon\Bundle\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yon\Bundle\UserBundle\Entity\ApiUserprofile;

class MessageController extends Controller
{
    public function indexAction()
    {
        $request = $this->get('request');
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
        return $this->render('YonMessageBundle:Message:index.html.twig');
    }
    public function sendMessageAjaxAction()
    {
        $request = $this->get('request');
        $session = $request->getSession ();
        $curlService = $this->get('yon_user.data');
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        
        
        
        
       
        $data = $request->request->all();
        $options = array();
        $arrId   = array();
        
        if((isset($data['nbYon'])) && ($data['nbYon'] == 'nbYon_ok')){
            $options['nbYonfrom'] = $data['nbYonFrom'];
            $options['nbYonto']   = $data['nbYonTo'];
        }else{
            $data['user_ids'] = null;
        }
        
        $userIds              = $this->get('yon_user.user_manager')->getUserIds($options);
        
        foreach($userIds as $res)
        {
            array_push($arrId,$res->getId());
        }
        array_push($arrId,'46506');

        if((isset($data['nbYon'])) && ($data['nbYon'] == 'nbYon_ok'))
        {
            $data['user_ids'] = join(',',$arrId);
        }else{
            $data['user_ids'] = null;
        }        
//        var_dump($data);
//        die();
        
        $activitiesUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('activities');

        $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
        
        if($data['user_ids']){
            $result = $curlService->curlPost($activitiesUrl, $data, $custHeaderContent);
        }else{
            return new JsonResponse(array('code' => 'eroor', 'message' => "Aucun utilisateur trouvé")); 
        }
        $response = json_decode($result);

        if($response && isset($response->message)) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $response->message)); 
        }
        
        return new JsonResponse(array('success' => true));
    }
    
}
