<?php

namespace Yon\Bundle\BadgeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BadgeController extends Controller
{
    /**
     * 
     * @param Request $request
     * @return type
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $tBreadcrumbs = array();
        $oBreadcrumb = new \stdClass();
        $oBreadcrumb->label= 'Badge';
        $oBreadcrumb->href = '';
        $tBreadcrumbs[] = $oBreadcrumb;
        
        $em = $this->getDoctrine()->getEntityManager();
        $apiAppsettings = $em->getRepository('YonUserBundle:ApiAppsettings')->find(1);
        
        
        if ($request->getMethod() == 'POST' ) {
            if ($request->getMethod() == 'POST') {
                $data = $request->request->all();
                
                $appSettingsUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('app_settings') ;
            
                $curlService = $this->get('yon_user.data');

                //set api header
                $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
                
                $result = $curlService->curlPut($appSettingsUrl, $data, $customerHeader);
            
                //traitement des redirection si OK ou pas
                $response = json_decode($result);
                
                if($response && isset($response->message) && $response->message != ''){
                    $this->get('session')->getFlashBag()->add('error', sprintf(
                        $response->message
                    ));
                } else {
                    $this->get('session')->getFlashBag()->add('success', sprintf(
                        'Badge bien enregistré.'
                    ));
                }
                
            } else {
//               
            }
            return $this->redirect($this->generateUrl('yon_badge_homepage'));
        }

        return array(
            'apiAppsettings' => $apiAppsettings,
            'tBreadcrumbs' => $tBreadcrumbs
        );
        
    }
}
