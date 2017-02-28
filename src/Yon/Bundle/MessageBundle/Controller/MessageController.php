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
        $arrFixedId   = array();
        $arrClassement   = array();
//        var_dump($data);
       
        if((isset($data['nbYon'])) && ($data['nbYon'] == 'nbYon_ok')){
            $options['nbYon']     = $data['nbYon'];
            $options['nbYonfrom'] = $data['nbYonFrom'];
            $options['nbYonto']   = $data['nbYonTo'];
        }
            
            
        if((isset($data['nbChallenge'])) && ($data['nbChallenge'] == 'nbChallenge_ok'))
        {
            $options['nbChallenge']      = $data['nbChallenge'];
            $options['nbch']             = $data['nbChallengeP'];
            if($data['nbChallengeFrom'] != ""){
                $datetime = new \DateTime();
                $newDate = $datetime->createFromFormat('d/m/Y H:i:s', $data['nbChallengeFrom'].":00");
                $options['d_nbch1']   = $newDate->format('Y-m-d H:i:s');
            }
            if($data['nbChallengeonTo'] != ""){
                $datetime = new \DateTime();
                $newDate = $datetime->createFromFormat('d/m/Y H:i:s', $data['nbChallengeonTo'].":00");
                $options['d_nbch2']   = $newDate->format('Y-m-d H:i:s');
            }
        }
        
        
        if((isset($data['nbPlayed'])) && ($data['nbPlayed'] == 'nbPlayed_ok')){
            
            /*$options['nbP']      = $data['nbPlayedP'];
            $options['d_nbP1']   = $data['nbPlayedFrom'];
            $options['d_nbP2']   = $data['nbPlayedTo'];*/
            $options['nbPlayed'] = $data['nbPlayed'];
            $options['nbP']      = $data['nbPlayedP'];
            if($data['nbPlayedFrom'] != ""){
                $datetime = new \DateTime();
                $newDatepl = $datetime->createFromFormat('d/m/Y H:i:s', $data['nbPlayedFrom'].":00");
                $options['d_nbpl1']   = $newDatepl->format('Y-m-d H:i:s');
            }
            if($data['nbPlayedTo'] != ""){
                $datetime = new \DateTime();
                $newDatepl = $datetime->createFromFormat('d/m/Y H:i:s', $data['nbPlayedTo'].":00");
                $options['d_nbpl2']   = $newDatepl->format('Y-m-d H:i:s');
            }
        }
        if(!empty($options['nbYon']) || !empty($options['nbChallenge']) || !empty($options['nbPlayed'])){
            
            $userIds              = $this->get('yon_user.user_manager')->getUserIds($options);
            if($userIds){
                foreach($userIds as $res)
                {
                    array_push($arrId,$res->getUser()->getId());
                }
            }
                
        }
        
        // si classement checked
        if((isset($data['classementChecked'])) && ($data['classementChecked'] == 'classement_ok')){
            $classementOption = array();
            if($data['classementFrom'] != ""){
                $classementOption['minClassement'] = $data['classementFrom'];
            }
            if($data['classementTo'] != ""){
                $classementOption['maxClassement'] = $data['classementTo'];
            }
            if($data['classementContest'] != ""){
                $classementOption['contestId'] = $data['classementContest'];
            }
            
             $usersResult   = $this->get('yon_rank.rank_manager')->getUsersBy($classementOption)->getResult();
             if($usersResult){
                foreach($usersResult as $usersResulti)
                {
                    array_push($arrClassement, $usersResulti->getUser()->getId());
                }
                if(count($arrId) > 0 && count($arrClassement)) {
                    $arrId = array_intersect($arrId, $arrClassement);
                }
             }
        }
        
        if((isset($data['fixIds'])) && ($data['fixIds'] == 'fixIds')){
            $tUserProfileIds = explode(',', $data['ids']);
            foreach ($tUserProfileIds as $userProfileId) {
                $utilisateur = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:ApiUserprofile')->find($userProfileId);
                if($utilisateur){
                    array_push($arrFixedId, $utilisateur->getUser()->getId());
                }
            }
            if(count($arrId) > 0 && count($arrFixedId) ) {
                $arrId = array_intersect($arrId, $arrFixedId);
            }
        }
        
        array_push($arrId,46506);

        if(
                ((isset($data['nbYon'])) && ($data['nbYon'] == 'nbYon_ok')) ||
                ((isset($data['nbChallenge'])) && ($data['nbChallenge'] == 'nbChallenge_ok')) ||
                ((isset($data['nbPlayed'])) && ($data['nbPlayed'] == 'nbPlayed_ok')) ||
                ((isset($data['fixIds'])) && ($data['fixIds'] == 'fixIds')) ||
                ((isset($data['classementChecked'])) && ($data['classementChecked'] == 'classement_ok'))
          )
        {
           $data['user_ids'] = join(',',$arrId);
        }else{
            $data['user_ids'] = null;
        }

//        var_dump($data);die();
        
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
