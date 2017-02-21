<?php

namespace Yon\Bundle\ParisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;
use Yon\Bundle\ParisBundle\Entity\ApiContest;
use Yon\Bundle\ParisBundle\Entity\ApiHashtag;
use Yon\Bundle\ParisBundle\Entity\ApiContestchallenge;
use Yon\Bundle\ParisBundle\Form\ApiChallengeType;
use Yon\Bundle\ParisBundle\Entity\ApiTrendingTopics;
use Yon\Bundle\ParisBundle\Util\UniqueId;
/**
 * ApiChallenge controller.
 *
 */
class ApiChallengeController extends Controller
{
    
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
    
    /**
     * Lists all ApiChallenge entities.
     *
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $routeName = $this->getRequest()->get('_route');              
        $session->set('lastVisitePage', $routeName);
        $authUserId = $request->get('userId');
        
        $apiChallenge = new ApiChallenge();
        $valStatus = $apiChallenge::$STATUS;
        // pour generer le menu contest dans le popup
        $em = $this->getDoctrine()->getManager();
        $apiContests = $em->getRepository('YonParisBundle:ApiContest')->findAll();
        $tApiContests = array();
        $oCont = new \stdClass();
        $oCont->text = 'Choisir le concours';
        $oCont->value = '';
        $tApiContests[] = $oCont;
        foreach ($apiContests as $oContest) {
            $oCont = new \stdClass();
            $oCont->text = $oContest->getName();
            $oCont->value = $oContest->getId();
            $tApiContests[] = $oCont;
        }
        
        //$apiChallenges = $em->getRepository('YonParisBundle:ApiChallenge')->findAll();
        return $this->render('YonParisBundle:Paris:index.html.twig', array(
            'apiContests' => json_encode($tApiContests),
            'authUserId' => $authUserId,
            'valStatus'=>$valStatus,
        ));
    }
    
    public function parisListAjaxAction(Request $request)
    {
        
        $filters  = $this->getFilters($request);
        $sortings = $this->getSortings($request, array(
            'p.id',
            'p.title',
            'pu.firstName',
            'p.startDate',
            'p.endDate',
            'ph.tag',
            'p.betsCount',
            'p.popularityScore',
            'pc.name',
        ));
        
        $status = $request->get('status', null);
        $coucoursId = $request->get('coucoursId', null);
        $from = $request->get('amp;from', null);
        $userId = $request->get('authUserId', null);
//        $userId = $request->get('userId', 46385);
        
        $options = array(
            'search' => $request->query->get('sSearch')
        );
        if(isset($status) && !empty($status)){
            $options['status'] = $status;
        } else {
            if( $status == 0 ) {
                $options['status'] = $status;
            }
        }
        
        $session    = $request->getSession ();
        $routeName  = $this->getRequest()->get('_route');   
        $LastparisAjaxParams = array('authUserId'=> $userId);
        if($status){
            $LastparisAjaxParams['status'] = $request->get('status', null);
        }
        $parisListAjaxUrl = $this->generateUrl('yon_paris_list_ajax', $LastparisAjaxParams , true);
        $session->set('parisListAjaxUrl', $parisListAjaxUrl);
        $session->set('statusValue', $status);
        
        if(isset($coucoursId) && !empty($coucoursId)){
            $options['coucoursId'] = $coucoursId;
        }
        
        if($userId){
            $options['userId'] = $userId;
        }
        
        $jTemplate = 'YonParisBundle:Paris:parisListAjax.json.twig';
        if(isset($from) && !empty($from)){
            switch ($from) {
                case 'editConcours':
                    $jTemplate = 'YonParisBundle:Paris:parisListAjaxFromEditConcours.json.twig';
                break;
                default:
                    $jTemplate = 'YonParisBundle:Paris:parisListAjax.json.twig';
                break;
            }
        }
        
        $nbParis         = $this->get('yon_paris.paris_manager')->getNbParisBy($options);
        $parisResult   = $this->get('yon_paris.paris_manager')->getParisBy($options, $filters, $sortings)->getResult();

        $content = $this->getDataJson(
            $request,
            $nbParis['nbParis'],
            $nbParis['nbParis'],
            $parisResult,
            $jTemplate
        );

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Creates a new ApiChallenge entity.
     *
     */
    public function newWithoutAPIAction(Request $request)
    {
        
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        //gerer si duplicated
        $duplicateFrom = $request->get('duplicateFrom');
        $baseParis = null;
        if($duplicateFrom){
            $entity = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find((int)$duplicateFrom);
            if($entity){
                $name = new \stdClass();
                $concours = "";
                foreach ( $entity->getContestChallenge() as $absensi  ){
                    $concours = $absensi->getContest()->getId();
                }

                $name->value = $entity->getTitle();
    //            $name->label = $entity->getUser()->getUsername();
                $name->label = $entity->getTitle();
                $name->title = $entity->getTitle();
                $name->endDate = date_format($entity->getEndDate(), 'd/m/Y H:i');
                $name->result = $entity->getResult();
                $name->color = $entity->getColor();
                $name->concours = $concours;
                $name->startDate = date_format($entity->getStartDate(), 'd/m/Y H:i');
                $name->hashtag = $entity->getHashtag() ? $entity->getHashtag()->getId() : 0;
                $name->user = $entity->getUser()->getId();
                $baseParis = $name;
            }
        }
        
        $apiChallenge = new ApiChallenge();
        $form = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiChallengeType', $apiChallenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $data = $request->request->all();
            if( (int)$data['to_belong_to_user'] > 0 ){
                $authUserId = $data['to_belong_to_user'];
                
            } else {
                $currentAuthUser = $session->get ( 'user_infos');
                $authUserId = $currentAuthUser->id;
            }
            $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
            $apiChallenge->setUser($authUser);
            
            // durée
            $duration = isset($data['api_challenge']['duration']) ? $data['api_challenge']['duration'] : 1;
            
            $concours = isset($data['api_challenge']['contest']) ? $data['api_challenge']['contest'] : null;
            
            if($concours){
                $contestChallenge = new \Yon\Bundle\ParisBundle\Entity\ApiContestchallenge();
                
                $oContest = $authUser = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find($concours);
                $contestChallenge->setCreation(new \DateTime());
                $contestChallenge->setContest($oContest);
                $contestChallenge->setChallenge($apiChallenge);
                
            }
            
            //create hashtag
             if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
                $newHashtag = new ApiHashtag();
                $newHashtag->setTag($data['api_challenge']['hashtag_user']);
                
                $em->persist($newHashtag);
                $em->flush();
                
                $apiChallenge->setHashtag($newHashtag);
            }
            
            /*
            $startDate1 = $apiChallenge->getStartDate();
            $startDate2 = $apiChallenge->getStartDate();
            $apiChallenge->setStartDate($startDate1);
            $endDate = clone $apiChallenge->getStartDate();
            $endDate = $endDate->add(new \DateInterval('PT'.$duration.'H'));
            $apiChallenge->setEndDate($endDate);
            */
            
            $em->persist($apiChallenge);
            $em->flush();
            
            if($concours){
                $em->persist($contestChallenge);
                $em->flush();
            }
            
            $this->get('session')->getFlashBag()->add('success', sprintf('un paris a été bien ajouté!.'));

            return $this->redirectToRoute('apichallenge_index');
        } else {
//            var_dump($form->getErrorsAsString());
//             $this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'enregistrement ');
//            $regex = '/ERROR:\s(.*)/';
//            preg_match_all($regex, $form->getErrorsAsString(), $matches);
//            
//            foreach ($matches[1] as $error) {
//                $this->get('session')->getFlashBag()->add('error',$error);
//            }
        }

        return $this->render('YonParisBundle:Paris:new.html.twig', array(
            'apiChallenge' => $apiChallenge,
            'form' => $form->createView(),
            'baseParis' => json_encode((array)$baseParis)
        ));
    }
    
    /**
     * Creates a new ApiChallenge entity via API.
     *
     */
    public function newAction(Request $request)
    {
        
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        //gerer si duplicated
        $duplicateFrom = $request->get('duplicateFrom');
        $baseParis = null;
        if($duplicateFrom){
            $entity = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find((int)$duplicateFrom);
            if($entity){
                $name = new \stdClass();
                $concours = "";
                foreach ( $entity->getContestChallenge() as $absensi  ){
                    $concours = $absensi->getContest()->getId();
                }

                $name->value = $entity->getTitle();
    //            $name->label = $entity->getUser()->getUsername();
                $name->label = $entity->getTitle();
                $name->title = $entity->getTitle();
                $finDate = date_format($entity->getEndDate(), 'Y-m-d H:i'); 
                $dateFin = new \DateTime($finDate);
                $dateFin->setTimezone(new \DateTimeZone('Europe/Paris'));
                $name->endDate = $dateFin->format('d/m/Y H:i');
                
                $name->result = $entity->getResult();
                $name->color = $entity->getColor();
                $name->concours = $concours;
                $debDate = date_format($entity->getStartDate(), 'Y-m-d H:i');               
                $dateDeb = new \DateTime($debDate);
                $dateDeb->setTimezone(new \DateTimeZone('Europe/Paris'));
                $name->startDate = $dateDeb->format('d/m/Y H:i');
//                $name->hashtag = $entity->getHashtag() ? $entity->getHashtag()->getId() : 0;
                $name->user = $entity->getUser()->getId();
                
                //set hashtag defaul value
                $isTrending = false;
                if($entity->getHashtag()){
                    $oApiTrendingTopics = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiFeaturedhashtag')->findOneBy(array('hashtag' => $entity->getHashtag()));
                    if($oApiTrendingTopics){
                        $name->trendingTopics = $oApiTrendingTopics->getId();
                        $isTrending = true;
                    } else {
                        $name->hashtagUser = $entity->getHashtag()->getTag();
                    }
                }
                $name->isTrending = $isTrending;
        
                $baseParis = $name;
                
            }
        }
        
        $apiChallenge = new ApiChallenge();
        $form = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiChallengeType', $apiChallenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $request->request->all();
            
            //prepare parametre pour envoyer vers api           
            $tParams = array();
            $tParamsCoupons     = array();
            $tParams['title'] = $data['api_challenge']['title'];
            $tParams['color'] = $data['api_challenge']['color'];
            $tParams['delayed_result'] = $data['api_challenge']['result'];
            $tParams['prize'] = $data['api_challenge']['prize'];
            $tParams['alert_message'] = $data['api_challenge']['alertMessage'];

            //hashatag
//            if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
//                $tParams['hashtag'] =  $data['api_challenge']['hashtag_user'];
//            } elseif ($apiChallenge->getHashtag()) {
//                $tParams['hashtag'] =  $apiChallenge->getHashtag()->getTag();
//            }
            
            if(isset($data['api_challenge']['choice_hashtag']) && $data['api_challenge']['choice_hashtag'] != '' ){
                if($data['api_challenge']['choice_hashtag'] == 'manuel'){
                    if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
                       $tParams['hashtag'] = str_replace('#', '', $data['api_challenge']['hashtag_user']);
                    }
                } elseif ($data['api_challenge']['choice_hashtag'] == 'trends') {
                    if(isset($data['api_challenge']['trendingTopics']) && $data['api_challenge']['trendingTopics'] != '' ){
                        $oTrendingTopic = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiFeaturedhashtag')->find($data['api_challenge']['trendingTopics']);
                        if($oTrendingTopic){
                            $tParams['hashtag'] = $oTrendingTopic->getHashtag()->getTag();
                        }
                    }
                }
            }
            
            
            
//            $tParams['time_to_end'] = floatval($data['api_challenge']['time_to_end']);
            $tParams['start_date'] = $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
            $tParams['end_date'] = $apiChallenge->getEndDate()->format(\DateTime::ISO8601);
//            $tParams['delayed_result'] = $data['api_challenge']['delayed_result'];
            $tParams['bet_price'] = $data['api_challenge']['betPrice'];
            if((int)$data['api_challenge']['status'] == 5){
                $tParams['draft'] = 1;
            }else{
                $tParams['draft'] = 0;
            }       
//            $tParams['draft'] = 1;
//            echo $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
           // var_dump( $tParams);
            $challengeUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges') ;
            
            $curlService = $this->get('yon_user.data');
            
            //set api header
            $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
            if( (int)$data['to_belong_to_user'] > 0 ){
                $customerHeader[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
            }
                
            $result = $curlService->curlPost($challengeUrl, $tParams, $customerHeader);
            
            //traitement des redirection si OK ou pas
            $response = json_decode($result);
            
            if($response && isset($response->id) && $response->id > 0){
                
                //add to contest if need
                $concours = isset($data['api_challenge']['contest']) ? $data['api_challenge']['contest'] : null;
                if($concours){
                    $ContestChallengeUrl = $this->container->getParameter('api_url').'/contests/'.$concours.'/challenges';
                    $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
                    $tParamsContestChallenge = array('challenge_id' => $response->id);
                    
                    $result = $curlService->curlPost($ContestChallengeUrl, $tParamsContestChallenge, $custHeaderContent);
                }
                
                //add to coupons
                $custHeaderContents = array('Authorization: '. $session->get ( 'yon_token')); 
                $couponsUrl         = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons') ;
                
                $tParamsCoupons['challenge_id'] =  $response->id;
                $tParamsCoupons['type']         =  $data['f_coupon']['type'];

                $tParamsCoupons['title']        =  $data['f_coupon']['title'];
                $tParamsCoupons['short_title']  =  $data['f_coupon']['short_title'];
                $tParamsCoupons['message']      =  $data['f_coupon']['message'];
                $tParamsCoupons['amount']       =  (int)$data['f_coupon']['amount'];
                
                $nameUniqId = UniqueId::generateRandomString(6);
                $tParamsCoupons['name'] =  "admin-".$nameUniqId;
                
                $resultCouponUrl   = $curlService->curlPost($couponsUrl, $tParamsCoupons, $custHeaderContents);
                
               $this->get('session')->getFlashBag()->add('success', sprintf('un paris a été bien ajouté!.')); 
            } else {
               $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
            }
            
            return $this->redirectToRoute('apichallenge_index');
        } else {
//            var_dump($form->getErrorsAsString());
//             $this->get('session')->getFlashBag()->add('error', 'Erreur lors de l\'enregistrement ');
//            $regex = '/ERROR:\s(.*)/';
//            preg_match_all($regex, $form->getErrorsAsString(), $matches);
//            
//            foreach ($matches[1] as $error) {
//                $this->get('session')->getFlashBag()->add('error',$error);
//            }
        }
        $typeCoupon = ApiChallenge::$TYPE_COUPON; 
        return $this->render('YonParisBundle:Paris:new.html.twig', array(
            'apiChallenge' => $apiChallenge,
            'typeCoupon' => $typeCoupon,
            'form' => $form->createView(),
            'baseParis' => json_encode((array)$baseParis)
        ));
    }

    /**
     * Finds and displays a ApiChallenge entity.
     *
     */
    public function showAction(ApiChallenge $apiChallenge)
    {
        $deleteForm = $this->createDeleteForm($apiChallenge);

        return $this->render('YonParisBundle:Paris:show.html.twig', array(
            'apiChallenge' => $apiChallenge,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ApiChallenge entity.
     *
     */
    public function editAction(Request $request, ApiChallenge $apiChallenge)
    {
        
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $currentAuthUser = $session->get ( 'user_profil_id');
        //echo $currentAuthUser;
        
//        $authUserId = $currentAuthUser->id;
//        echo $authUserId;
        
        $deleteForm = $this->createDeleteForm($apiChallenge);
        $editForm = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiChallengeType', $apiChallenge);
        foreach ( $apiChallenge->getContestChallenge() as $absensi  ){
            $editForm->get('contest')->setData($absensi->getContest());
        }
        $editForm->get('result')->setData($apiChallenge->getDelayedResult());
        
        if($apiChallenge->getStatus() != 5){
            $editForm->get('status')->setData(0);
        }
        //set hashtag defaul value
        $isTrending = false;
        if($apiChallenge->getHashtag()){
            $oApiTrendingTopics = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiFeaturedhashtag')->findOneBy(array('hashtag' => $apiChallenge->getHashtag()));
            if($oApiTrendingTopics){
                $editForm->get('choice_hashtag')->setData('trends');
                $editForm->get('trendingTopics')->setData($oApiTrendingTopics);
                $isTrending = true;
            } else {
                $editForm->get('choice_hashtag')->setData('manuel');
                $editForm->get('hashtag_user')->setData($apiChallenge->getHashtag()->getTag());
            }
        }
        
        // Récuperation coupons à partir de l'id du paris (challenges/<id>/coupons)
        $curlServices = $this->get('yon_user.data');
        $couponChallengeUrl = $this->container->getParameter('api_url').$this->container->getParameter('challenges').'/'.$apiChallenge->getId().$this->container->getParameter('coupons');
        $post_data =  array('token' => $session->get ( 'yon_token'));
        $resultCouponChallenge = $curlServices->curlGet($couponChallengeUrl,$post_data);    
        
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $data = $request->request->all();
            
            //prepare parametre pour envoyer vers api
            $tParams = array();
            $tParamsCoupons = array();
            $tParams['title'] = $data['api_challenge']['title'];
            $tParams['color'] = $data['api_challenge']['color'];
            $tParams['delayed_result'] = $data['api_challenge']['result'];
            $tParams['prize'] = $data['api_challenge']['prize'];
            
            //hashtag
//            if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
//                $tParams['hashtag'] =  $data['api_challenge']['hashtag_user'];
//            } elseif ($apiChallenge->getHashtag()) {
//                $tParams['hashtag'] =  $apiChallenge->getHashtag()->getTag();
//            }
            if(isset($data['api_challenge']['choice_hashtag']) && $data['api_challenge']['choice_hashtag'] != '' ){
                if($data['api_challenge']['choice_hashtag'] == 'manuel'){
                    if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
                       $tParams['hashtag'] =  $data['api_challenge']['hashtag_user'];
                    }
                } elseif ($data['api_challenge']['choice_hashtag'] == 'trends') {
                    if(isset($data['api_challenge']['trendingTopics']) && $data['api_challenge']['trendingTopics'] != '' ){
                        $oTrendingTopic = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiFeaturedhashtag')->find($data['api_challenge']['trendingTopics']);
                        if($oTrendingTopic){
                            $tParams['hashtag'] = $oTrendingTopic->getHashtag()->getTag();
                        }
                    }
                }
            }
            
//            $tParams['time_to_end'] = floatval($data['api_challenge']['time_to_end']);
            $tParams['start_date'] = $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
            $tParams['end_date'] = $apiChallenge->getEndDate()->format(\DateTime::ISO8601);
//            $tParams['delayed_result'] = $data['api_challenge']['delayed_result'];
            $tParams['bet_price'] = $data['api_challenge']['betPrice'];
                if((int)$data['api_challenge']['status'] == 5){
                $tParams['draft'] = 1;
            }/*else{
                $tParams['draft'] = 0;
            }*/
//            echo $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
           // var_dump( $tParams);
            $editChallengeUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges').'/'. $apiChallenge->getId();
            
            $curlService = $this->get('yon_user.data');
            
            //set api header
            $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
            if( (int)$data['to_belong_to_user'] > 0  ){
                $customerHeader[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
            }
                
            $result = $curlService->curlPatch($editChallengeUrl, $tParams, $customerHeader);
            
            //traitement des redirection si OK ou pas
            $response = json_decode($result);
//            var_dump($result);die;
            
            // edit or delete coupon
            if(($data['f_coupon']['type'] !== "")){
                $idCoupons = $data['couponId'];
                if(isset($data['f_coupon']['check'])){
                    $tParamsCoupons['type']         =  $data['f_coupon']['type'];
                    $tParamsCoupons['challenge_id'] =  $apiChallenge->getId();
                    $tParamsCoupons['title']        =  $data['f_coupon']['title'];
                    $tParamsCoupons['short_title']  =  $data['f_coupon']['short_title'];
                    $tParamsCoupons['message']      =  $data['f_coupon']['message'];
                    $tParamsCoupons['amount']       =  (int)$data['f_coupon']['amount'];
                    $nameUniqId = UniqueId::generateRandomString(6);
                    $tParamsCoupons['name'] =  "admin-".$nameUniqId;
                    if((int)$idCoupons > 0){
                        $editCouponUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons').'/'. $idCoupons;
                        $result = $curlService->curlPatch($editCouponUrl, $tParamsCoupons, $customerHeader);
                    } else {
                        $custHeaderContents = array('Authorization: '. $session->get ( 'yon_token')); 
                        $couponsUrl         = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons') ;
                        
                        $resultCouponUrl   = $curlService->curlPost($couponsUrl, $tParamsCoupons, $custHeaderContents);
                    }
                    
                }else{
                    $delCouponUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons').'/'. $idCoupons;
                    $custHeaderContents = array('Authorization: '. $session->get ( 'yon_token'));
                    $result = $curlService->curlDelete($delCouponUrl, $custHeaderContents);
                    $response = json_decode($result);
                }
            }         
            
            if($response && isset($response->id) && $response->id > 0){
                
                //add to contest if need
                $concours = isset($data['api_challenge']['contest']) ? $data['api_challenge']['contest'] : null;
                if($concours){
                    $ContestChallengeUrl = $this->container->getParameter('api_url').'/contests/'.$concours.'/challenges';
                    $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
                    $tParamsContestChallenge = array('challenge_id' => $response->id);
                    
                    $result = $curlService->curlPost($ContestChallengeUrl, $tParamsContestChallenge, $custHeaderContent);
                }
                                              
               $this->get('session')->getFlashBag()->add('success', sprintf('un paris a été bien modifié!.')); 
            } else {
               $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
            }
            
            $sessionLastVisitPage = $session->get ( 'lastVisitePage');
            
            if($sessionLastVisitPage && isset($sessionLastVisitPage[1]) && is_array($sessionLastVisitPage)){
                return $this->redirectToRoute($sessionLastVisitPage[0], array('id' => $sessionLastVisitPage[1]));
            } elseif($sessionLastVisitPage){
                return $this->redirectToRoute($sessionLastVisitPage);
            } else {
                return $this->redirectToRoute('apichallenge_index');
            }
            
        }
        $typeCoupon = ApiChallenge::$TYPE_COUPON; 
        $rsCoupons = json_decode($resultCouponChallenge);
        $items = $rsCoupons->items;
        if(!empty($items)){
            $rsItems = $items[0];
        }else{
            $rsItems = "";
        }
        
        return $this->render('YonParisBundle:Paris:edit.html.twig', array(
            'apiChallenge' => $apiChallenge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'isTrending' => $isTrending,
            'typeCoupon'=>$typeCoupon,
            'resultCouponChallenge'=>$rsItems,
        ));
    }
    
    public function testWebserviceAction(Request $request)
    {
        $session = $request->getSession ();
        $curlService = $this->get('yon_user.data');
        $delCouponUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons').'/'. 314468;
        
        $custHeaderContents = array('Authorization: '. $session->get ( 'yon_token'));
        $result = $curlService->curlDelete($delCouponUrl, $custHeaderContents);
        $response = json_decode($result);
        var_dump($response);
        die();
    }

    /**
     * Deletes a ApiChallenge entity.
     *
     */
    public function deleteAction(Request $request, ApiChallenge $apiChallenge)
    {
        $form = $this->createDeleteForm($apiChallenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($apiChallenge);
            $em->flush();
        }

        return $this->redirectToRoute('apichallenge_index');
    }

    /**
     * Creates a form to delete a ApiChallenge entity.
     *
     * @param ApiChallenge $apiChallenge The ApiChallenge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApiChallenge $apiChallenge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('apichallenge_delete', array('id' => $apiChallenge->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function parisAutocompleteAction(Request $request) {

        $names = array();
        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();

        $paris = $em->getRepository('YonParisBundle:ApiChallenge')->createQueryBuilder('c')
                ->where('c.title like :name')
                ->setParameter('name', '%'.$term . '%')->setFirstResult(0)->setMaxResults(10);
        
        $paris = $paris->getQuery();
        $paris = $paris->getResult();
        
        foreach ($paris as $entity) {
            
            $name = new \stdClass();
            $concours = "";
            foreach ( $entity->getContestChallenge() as $absensi  ){
                $concours = $absensi->getContest()->getId();
            }
            
            $name->value = $entity->getTitle();
//            $name->label = $entity->getUser()->getUsername();
            $name->label = $entity->getTitle();
            $name->title = $entity->getTitle();
            $name->endDate = date_format($entity->getEndDate(), 'd/m/Y H:i');
            $name->result = $entity->getResult();
            $name->color = $entity->getColor();
            $name->concours = $concours;
            $name->startDate = date_format($entity->getStartDate(), 'd/m/Y H:i');
            $name->hashtag = $entity->getHashtag() ? $entity->getHashtag()->getId() : 0;
            $name->user = $entity->getUser()->getId();
            
            $names[] = $name;
            
        }

        $response = new JsonResponse();
        $response->setData($names);

        return $response;
    }
    
    public function resetConcoursAjaxAction(Request $request)
    {
        $data = $request->request->all();
        
        if( !(isset($data['challenge']) && isset($data['contest'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'messate' => 'parameter missing challenge, contest')); 
        }
        
        $oApiContestchallenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContestchallenge')->findOneBy(array(
            'challenge' => $data['challenge'],
            'contest' => $data['contest']
        ));
        
        if($oApiContestchallenge){
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($oApiContestchallenge);
            $em->flush();
        } else {
           return new JsonResponse(array('code' => 'eroor', 'message' => 'error lors de suppression du paris du concours')); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    public function twitterHashtagAjaxAction(Request $request)
    {
        $twitter = $this->get('endroid.twitter');
        // Or retrieve the timeline using the generic query method
        $tParams = array (
            //'id' => 1      // global WOEID (Where On Earth ID)
            'id' => 23424819 // france WOEID (Where On Earth ID)
        );
        $response = $twitter->query('trends/place', 'GET', 'json', $tParams);
        
        $tweets = json_decode($response->getContent());
//        var_dump($tweets[0]->trends);die;
        return new JsonResponse($tweets);
    }
    
    public function WithoutAPIaddChallengeToContestAjaxAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $data = $request->request->all();
        
        if( !(isset($data['challenge']) && isset($data['contest'])) ) {
            return new JsonResponse(array('code' => 'error', 'message' => 'parameter missing challenge, contest')); 
        }
        
        $oApiContestchallenges = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContestchallenge')->findBy(array(
            'challenge' => $data['challenge'],
            'contest' => $data['contest']
        ));
        
        foreach ($oApiContestchallenges as $oRem) {
            $em->remove($oRem);
            $em->flush();
        }
        
        
        $oApiContestchallenge = new ApiContestchallenge();
        $challenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find($data['challenge']);
        $contest = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find($data['contest']);

        if($contest && $challenge) {
            $oApiContestchallenge->setChallenge($challenge);
            $oApiContestchallenge->setContest($contest);


            $em->persist($oApiContestchallenge);
            $em->flush();
        } else {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'error lors d\'ajout du paris au concours')); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    public function addChallengeToContestAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        $curlService = $this->get('yon_user.data');
        $data = $request->request->all();
               
        if( !(isset($data['challenge']) && isset($data['contest'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'parameter missing challenge, contest')); 
        }
        
        $ContestChallengeUrl = $this->container->getParameter('api_url').'/contests/'.$data['contest'].'/challenges';
        
        $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
        $tParamsContestChallenge = array('challenge_id' => $data['challenge']);

        $result = $curlService->curlPost($ContestChallengeUrl, $tParamsContestChallenge, $custHeaderContent);
        
        $response = json_decode($result);
        
        if($response && isset($response->message)) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $response->message)); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    public function addToTrendsAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
               
        $curlService = $this->get('yon_user.data');
        $htagChallengeUrl = $this->container->getParameter('api_url').$this->container->getParameter('hashtags');
        //$tParamsHtag['featured_hashtag_ids'] = '67,45,';
        $tParamsHtag['tag'] = $data['tag'];
        $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
        $result = $curlService->curlPost($htagChallengeUrl, $tParamsHtag, $custHeaderContent);
        $response = json_decode($result);
        
        if($response && isset($response->id) && $response->id > 0){
                
                $arr_id = [];
                $oApiFeaturedhashtag = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiFeaturedhashtag')->findAll();

                foreach($oApiFeaturedhashtag as $res){
                    array_push($arr_id,$res->getHashtag()->getId());
                }
                
                if(!in_array($response->id,$arr_id)){
                    array_push($arr_id, $response->id);
                }
                
                $tParamsFeatures['featured_hashtag_ids'] = implode(',', $arr_id);
                $results = $curlService->curlPost($htagChallengeUrl, $tParamsFeatures, $custHeaderContent);
                $responses = json_decode($results);
                
                if($response && isset($response->message) && $response->message != ''){
                    return new JsonResponse(array('code' => 'eroor', 'message' => sprintf($response->message)));
                }
                
            } else {
               return new JsonResponse(array('code' => 'eroor', 'message' => sprintf($response->message)));
            }
        
            return new JsonResponse(array('code' => 'success'));
        /*die();        
        if( !(isset($data['tag'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'parameter missing tag')); 
        }
        try {
            $oApiTrendingTopics = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiTrendingTopics')->findOneBy(array('tag' => $data['tag']));
            if(!$oApiTrendingTopics){               
                $oApiTrendingTopics = new ApiTrendingTopics();
                $oApiTrendingTopics->setTag($data['tag']);

                $em->persist($oApiTrendingTopics);
                $em->flush();
            }
            
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
       
        
        return new JsonResponse(array('code' => 'success'));*/
    }
    
    public function delToTrendsAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
        
        if( !(isset($data['id'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'messate' => 'parameter missing id')); 
        }
        try{
            $oApiTrendingTopics = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiTrendingTopics')->find($data['id']);
            $em->remove($oApiTrendingTopics);
            $em->flush();
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    public function deleteAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        $curlService = $this->get('yon_user.data');
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
        
        if( !(isset($data['challengeId'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'messate' => 'parameter missing id')); 
        }
        try{
            $delChallengeUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges').'/'. $data['challengeId'];
        
            $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));
//            if( (int)$data['to_belong_to_user'] > 0  ){
//                $custHeaderContent[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
//            }

            $result = $curlService->curlDelete($delChallengeUrl, $custHeaderContent);

            $response = json_decode($result);
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    /**
     * Lists all trends entities.
     *
     */
    public function trendsAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $twitter = $this->get('endroid.twitter');
        
        $tParams = array (
            //'id' => 1      // global WOEID (Where On Earth ID)
            'id' => 23424819 // france WOEID (Where On Earth ID)
        );        
        $twitterTopCinquants = null;
        try {
            $response = $twitter->query('trends/place', 'GET', 'json', $tParams);
            $tweets = json_decode($response->getContent());
            $twitterTopCinquants = $tweets[0]->trends;
            
        } catch (Exception $ex) {
            $tweets = array();
        } finally {
            if(!$twitterTopCinquants){
                $twitterTopCinquants = array();
            }
            
            // pour generer le menu contest dans le popup
            $em = $this->getDoctrine()->getManager();
            $apiTrendingTopics = $em->getRepository('YonParisBundle:ApiFeaturedhashtag')->findAll();
            $apiHashtags = $em->getRepository('YonParisBundle:ApiHashtag')->findBy(
                array('visible'=> 1), 
                array('range' => 'DESC'),
                6,0
            );
            

            return $this->render('YonParisBundle:Paris:trends.html.twig', array(
                'twitterTopCinquants' => $twitterTopCinquants,
                'apiHashtags' => $apiHashtags,
                'apiTrendingTopics' => $apiTrendingTopics,
            ));
        }
    }
    
    /**
     * Lists all moderate challenges.
     *
     */
    public function moderateAction(Request $request)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $filters = array();
        $filters['start'] = 0;
        $filters['length'] = 50;
        $sortings = array();
        $sortings['p.endDate'] = 'ASC';
        $options = array(
//            'search' => $request->query->get('sSearch')
        );
        $options['status'] = "";
        $options['state'] = "0";
        $options['isModerate'] = true;
        $em = $this->getDoctrine()->getManager();
        
        $apiChallenges   = $this->get('yon_paris.paris_manager')->getParisBy($options, $filters, $sortings)->getResult();
        
        //set state encours:2 et date moderation to now
        foreach ($apiChallenges as $apiChallenge){
            $apiChallenge->setState(2);
            $apiChallenge->setModerateAt(new \DateTime ());
            $em->persist($apiChallenge);
            $em->flush();
        }

        return $this->render('YonParisBundle:Paris:moderate.html.twig', array(
            'apiChallenges' => $apiChallenges,
        ));
    }
    
    public function resetModerateAction(Request $request)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $filters = array();
//        $filters['start'] = 0;
//        $filters['length'] = 50;
        $sortings = array();
//        $sortings['p.endDate'] = 'ASC';
        $options = array(
//            'search' => $request->query->get('sSearch')
        );
        $options['status'] = "";
        $options['state'] = "2";
        $options['resetModerate'] = true;
        $em = $this->getDoctrine()->getManager();
        
        $apiChallenges   = $this->get('yon_paris.paris_manager')->getParisBy($options, $filters, $sortings)->getResult();
        
        //set state encours:2 et date moderation to now
        foreach ($apiChallenges as $apiChallenge){
            $apiChallenge->setState(0);
            $apiChallenge->setModerateAt(NULL);
            $em->persist($apiChallenge);
            $em->flush();
        }
        
        echo 'reset: '. count($apiChallenges). ' paris';die;
    }
    
    public function resetModerateImmediatlyAction(Request $request)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $filters = array();
//        $filters['start'] = 0;
//        $filters['length'] = 50;
        $sortings = array();
//        $sortings['p.endDate'] = 'ASC';
        $options = array(
//            'search' => $request->query->get('sSearch')
        );
        $options['status'] = "";
        $options['state'] = "2";
//        $options['resetModerate'] = true;
        $em = $this->getDoctrine()->getManager();
        
        $apiChallenges   = $this->get('yon_paris.paris_manager')->getParisBy($options, $filters, $sortings)->getResult();
        
        //set state encours:2 et date moderation to now
        foreach ($apiChallenges as $apiChallenge){
            $apiChallenge->setState(0);
            $apiChallenge->setModerateAt(NULL);
            $em->persist($apiChallenge);
            $em->flush();
        }
        
        echo 'reset: '. count($apiChallenges). ' paris';die;
    }
    
    public function validateAllChallengeAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $currentAuthUser = $session->get ( 'user_infos');
        $authUserId = $currentAuthUser->id;
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
        
        if( !(isset($data['challengeId'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'parameter missing challengeId')); 
        }
        try {
            foreach ($data['challengeId'] as $id) {
                $oApiChallenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find($id);
                if($oApiChallenge){
                    $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
                    $oApiChallenge->setState(1);
                    $oApiChallenge->setModerateBy($authUser);
                    $em->persist($oApiChallenge);
                    $em->flush();
                }
            }
            
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
       
        
        return new JsonResponse(array('code' => 'success'));
    }
    public function validateOneChallengeAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $currentAuthUser = $session->get ( 'user_infos');
        $authUserId = $currentAuthUser->id;
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
        
        if( !(isset($data['id'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'parameter missing id')); 
        }
        try {
            $oApiChallenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find($data['id']);
            if($oApiChallenge){
                $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
                $oApiChallenge->setState(1);
                $oApiChallenge->setModerateBy($authUser);
                $em->persist($oApiChallenge);
                $em->flush();
            }
            
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
       
        
        return new JsonResponse(array('code' => 'success'));
    }
    
    public function lockOneChallengeAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $currentAuthUser = $session->get ( 'user_infos');
        $authUserId = $currentAuthUser->id;
        $em = $this->getDoctrine()->getEntityManager();
        
        $data = $request->request->all();
        
        if( !(isset($data['id'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'message' => 'parameter missing id')); 
        }
        try {
            $oApiChallenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find($data['id']);
            if($oApiChallenge){
                $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
                $oApiChallenge->setState(3);
                $oApiChallenge->setModerateBy($authUser);
                $em->persist($oApiChallenge);
                $em->flush();
            }
            
        } catch (Exception $ex) {
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
        }
        
        return new JsonResponse(array('code' => 'success'));
    }
}
