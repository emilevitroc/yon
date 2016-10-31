<?php

namespace Yon\Bundle\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        
        $activitiesUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('activities');

        $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));

        $result = $curlService->curlPost($activitiesUrl, $data, $custHeaderContent);

        $response = json_decode($result);

        if($response && isset($response->message)) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $response->message)); 
        }
        
        return new JsonResponse(array('success' => true));
    }
}
