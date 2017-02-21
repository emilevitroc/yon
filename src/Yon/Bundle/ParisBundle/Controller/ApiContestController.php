<?php

namespace Yon\Bundle\ParisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Yon\Bundle\ParisBundle\Entity\ApiContest;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;
use Yon\Bundle\ParisBundle\Form\ApiContestType;
use Yon\Bundle\ParisBundle\Entity\ApiHashtag;

/**
 * ApiContest controller.
 *
 */
class ApiContestController extends Controller
{
    /**
     * Lists all ApiContest entities.
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
        
        $em = $this->getDoctrine()->getManager();

        $apiContests = $em->getRepository('YonParisBundle:ApiContest')->findAll();
        
        return $this->render('YonParisBundle:Apicontest:index.html.twig', array(
            'apiContests' => $apiContests,
        ));
    }
    
    public function contestInfoAjaxAction(Request $request) {

        $id = $request->get('id');
        if( !$id ) {
            return new JsonResponse(array('code' => 'error', 'message' => 'parameter missing id')); 
        }
        $em = $this->getDoctrine()->getManager();

        $apiContest = $em->getRepository('YonParisBundle:ApiContest')->find($id);
        $name = new \stdClass();
        if($apiContest){
            $name->name = $apiContest->getName();
            $name->userId = $apiContest->getUser() ? $apiContest->getUser()->getId(): 0;
            $name->userProfileId = $apiContest->getUser() ? $apiContest->getUser()->getUser()->getId(): 0;
            $name->endDate = date_format($apiContest->getEndDate()->setTimezone(new \DateTimeZone('Europe/Paris')), 'd/m/Y H:i');
            $name->startDate = date_format($apiContest->getStartDate()->setTimezone(new \DateTimeZone('Europe/Paris')), 'd/m/Y H:i'); 
            $endDateP = clone $apiContest->getStartDate();
            $endDateP6 = clone $apiContest->getStartDate();
            $endDateProv = $endDateP->add(new \DateInterval('PT3H'));
            $endDateProv6 = $endDateP6->add(new \DateInterval('PT6H'));
            $name->endDateProvisoir = date_format($endDateProv, 'd/m/Y H:i'); 
            $name->endDateProvisoir6 = date_format($endDateProv6, 'd/m/Y H:i'); 
        }

        $response = new JsonResponse();
        $response->setData($name);

        return $response;
    }

    /**
     * Creates a new ApiContest entity.
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
        
        $apiContest = new ApiContest();
        $form = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiContestType', $apiContest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $request->request->all();
            if( (int)$data['to_belong_to_user'] > 0 ){
                $authUserId = $data['to_belong_to_user'];
                
            } else {
                $currentAuthUser = $session->get ( 'user_infos');
                $authUserId = $currentAuthUser->id;
            }
            $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
            $apiContest->setUser($authUser);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiContest);
            $em->flush();
            
            //create hashtag
            $newHashtag = new ApiHashtag();
            $newHashtag->setTag(str_replace('#', '', $apiContest->getName()));

            $em->persist($newHashtag);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('un concours a été bien ajouté!.'));
            return $this->redirectToRoute('apicontest_index');
        }

        return $this->render('YonParisBundle:Apicontest:new.html.twig', array(
            'apiContest' => $apiContest,
            'form' => $form->createView(),
        ));
    }
    
    public function newAction(Request $request)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        //Remplir le formulaire dupliqué
        $duplicateFrom = $request->get('duplicateFrom');
        $baseContest = null;
        $oApiChallenge = new ApiChallenge();
        $apiContest = new ApiContest();
        
        if($duplicateFrom){
            $entity = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find((int)$duplicateFrom);           
            if($entity){
                $name = new \stdClass();
                $concours = "";
                
                $name->name             = $entity->getName();
                $name->description      = $entity->getDescription();
                $name->challengeCount   = $entity->getPlannedChallengesCount();
                
                if($entity->getUser() !== null){
                    $name->user = $entity->getUser()->getId();
                }
                $finDate                = date_format($entity->getEndDate(), 'Y-m-d H:i'); 
                $dateFin                = new \DateTime($finDate);
//                $dateFin->setTimezone(new \DateTimeZone('Europe/Paris'));
                $name->endDate          = $dateFin->format('d/m/Y H:i');
                
                $debDate                = date_format($entity->getStartDate(), 'Y-m-d H:i');               
                $dateDeb                = new \DateTime($debDate);
//                $dateDeb->setTimezone(new \DateTimeZone('Europe/Paris'));
                $name->startDate        = $dateDeb->format('d/m/Y H:i');

                $baseContest            = $name;
                
            }            
        }
        //Fin remplissage formulaire dupliqué
        
        $apiContest = new ApiContest();
        $form = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiContestType', $apiContest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $request->request->all();
            // Preparer les données provenant de formulaire dupliqué et enregistrement de paris dédans
            if($duplicateFrom){   
                $curlService            = $this->get('yon_user.data');
                ///contests/{id} 
                /*$singleContestseUrl     = $this->container->getParameter('api_url').$this->container->getParameter('contests').'/'.(int)$duplicateFrom;
                $post_data              =  array('token' => $session->get ( 'yon_token'));
                $oResApiContest         = $curlService->curlGet($singleContestseUrl,$post_data); 
                $oApiContest            = json_decode($oResApiContest);*/
                            
                $oApiContest            = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find((int)$duplicateFrom);
                $oApiContestchallenges  = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContestchallenge')->findBy(array(
                    'contest' => (int)$duplicateFrom
                ));
                $startDateApiContest    = $oApiContest->getStartDate();
                $endDateApiContest      = $oApiContest->getEndDate();
                
                $dateDeb                = $apiContest->getStartDate();
                $dateFin                = $apiContest->getEndDate();
                
                // Enregistrement contest
                $tParamsFormDuplicate                               = array();
                $tParamsFormDuplicate['name']                       = str_replace(array(' ', '#'), array('',''), $data['api_contest']['name']);
                $tParamsFormDuplicate['description']                = $data['api_contest']['description'];
                $tParamsFormDuplicate['planned_challenges_count']   = $data['api_contest']['plannedChallengesCount'];

                $tParamsFormDuplicate['start_date']                 = $dateDeb->format(\DateTime::ISO8601);
                $tParamsFormDuplicate['end_date']                   = $dateFin->format(\DateTime::ISO8601);
                
                //set api header
                $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $customerHeader[]                                = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
                }

                $contestseUrl                                        = $this->container->getParameter('api_url').''.$this->container->getParameter('contests') ;
                
                
                $result                                              = $curlService->curlPost($contestseUrl, $tParamsFormDuplicate, $customerHeader);                
                // fin enregistrement contest
                
                //traitement des redirection et enregistrement challenge
                $responseContest = json_decode($result);
                if($responseContest && isset($responseContest->id) && $responseContest->id > 0){
                    if( (int)$data['to_belong_to_user'] > 0 ){
                        $apiContest = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:apiContest')->find($responseContest->id);
                        $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($data['to_belong_to_user']);
                        $apiContest->setUser($authUser);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($apiContest);
                        $em->flush();
                    }
                    
                    $d1         = strtotime($startDateApiContest->format('Y-m-d h:m'));
                    $d2         = strtotime($dateDeb->format('Y-m-d h:m'));
                    $datediff   = $d2 - $d1;
                    
                    $nbDayBetweenTwoDate = floor($datediff / (60 * 60 * 24));

                    // Enregistrement Challenge
                    foreach($oApiContestchallenges as $resChallenge){
                        // enregistrement challenge
                        // preparation parametre pour envoyer vers api  
                        $tParamsCoupons                                 = array();
                        $tParamsChallengeDuplicated                     = array();
                        $tParamsChallengeDuplicated['title']            = $resChallenge->getChallenge()->getTitle();
                        $tParamsChallengeDuplicated['color']            = $resChallenge->getChallenge()->getColor();
                        $tParamsChallengeDuplicated['delayed_result']   = ($resChallenge->getChallenge()->getDelayedResult() === null) ? '' : $resChallenge->getChallenge()->getDelayedResult();
                        $tParamsChallengeDuplicated['prize']            = ($resChallenge->getChallenge()->getPrize() === null) ? '': $resChallenge->getChallenge()->getPrize();
                        $tParamsChallengeDuplicated['alert_message']    = ($resChallenge->getChallenge()->getAlertMessage() === null) ? '' : $resChallenge->getChallenge()->getAlertMessage();                                              
                        $tParamsChallengeDuplicated['hashtag']          = $resChallenge->getChallenge()->getHashtag()->getTag();                       
                        
                        $startDate = clone $resChallenge->getChallenge()->getStartDate();
                        $endDate   = clone $resChallenge->getChallenge()->getEndDate();
                        if($nbDayBetweenTwoDate > 0){
                            $tParamsChallengeDuplicated['start_date']       = $startDate->add(new \DateInterval('P'.$nbDayBetweenTwoDate.'D'))->format(\DateTime::ISO8601);
                            $tParamsChallengeDuplicated['end_date']         = $endDate->add(new \DateInterval('P'.$nbDayBetweenTwoDate.'D'))->format(\DateTime::ISO8601);
                        }else{
                            $tParamsChallengeDuplicated['start_date']       = $startDate->format(\DateTime::ISO8601);
                            $tParamsChallengeDuplicated['end_date']         = $endDate->format(\DateTime::ISO8601);
                        }
                        $tParamsChallengeDuplicated['bet_price']        = $resChallenge->getChallenge()->getBetPrice();
                        
                        if((int)$resChallenge->getChallenge()->getStatus() == 5){
                            $tParamsChallengeDuplicated['draft']        = 1;
                        }else{
                            $tParamsChallengeDuplicated['draft']        = 0;
                        }
                        
                        $challengeUrl                                   = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges') ;
                        $curlService                                    = $this->get('yon_user.data');

                        //set api header
                        $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
                        if( (int)$data['to_belong_to_user'] > 0 ){
                            $customerHeader[]                           = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
                        }
                        
                        $result                                         = $curlService->curlPost($challengeUrl, $tParamsChallengeDuplicated, $customerHeader);                   
                        $responseChallenge                              = json_decode($result);
                        //traitement des redirection si OK ou pas
                        if($responseChallenge && isset($responseChallenge->id) && $responseChallenge->id > 0){
                            //add to contest if need
                            $concours = isset($responseContest->id) ? $responseContest->id : null;
                            if($concours){
                                $ContestChallengeUrl                    = $this->container->getParameter('api_url').'/contests/'.$concours.'/challenges';
                                $custHeaderContent                      = array('Authorization: '. $session->get ( 'yon_token'));
                                $tParamsContestChallenge                = array('challenge_id' => $responseChallenge->id);
                                $result                                 = $curlService->curlPost($ContestChallengeUrl, $tParamsContestChallenge, $custHeaderContent);
                            }

                            // enregistrement coupons
                            $couponChallengeUrl                         = $this->container->getParameter('api_url').$this->container->getParameter('challenges').'/'.(int)$resChallenge->getChallenge()->getId().$this->container->getParameter('coupons');
                            $post_data                                  =  array('token' => $session->get ( 'yon_token'));
                            $oApiCoupon                                 = $curlService->curlGet($couponChallengeUrl,$post_data); 
                            $oResApiCoupon                              = json_decode($oApiCoupon);
                            
                            
                            if($oResApiCoupon){
                                $custHeaderContents                     = array('Authorization: '. $session->get ( 'yon_token')); 
                                $couponsUrl                             = $this->container->getParameter('api_url').''.$this->container->getParameter('coupons') ;                             
                                $items = $oResApiCoupon->items;
                                if(!empty($items)){
                                    $rsItems = $items[0];
                                    $tParamsCoupons['challenge_id']         =  $responseChallenge->id;
                                    $tParamsCoupons['type']                 =  $rsItems->type;
                                    $tParamsCoupons['title']                =  $rsItems->popup->title;
                                    $tParamsCoupons['short_title']          =  $rsItems->popup->short_title;
                                    $tParamsCoupons['message']              =  $rsItems->popup->message;
                                    $tParamsCoupons['amount']               =  (int)$rsItems->amount;
                                    $tParamsCoupons['name']                 =  $rsItems->name;
                                    $resultCouponUrl                        = $curlService->curlPost($couponsUrl, $tParamsCoupons, $custHeaderContents);
                                }                       
                            }
                        } else {
                           $this->get('session')->getFlashBag()->add('error', sprintf($responseChallenge->message));
                        }
                    }
                   // fin enregistrement Challenge
                }
                $this->get('session')->getFlashBag()->add('success', sprintf('un concours a été dupliqué !.'));
            }else{
            
                //prepare parametre pour envoyer vers api
                $tParams = array();
                $tParams['name'] = str_replace(array(' ', '#'), array('',''), $data['api_contest']['name']);
                //$tParams['hashtag_id'] = str_replace(array(' ', '#'), array('',''), $data['api_contest']['name']);
                $tParams['description'] = $data['api_contest']['description'];
                $tParams['planned_challenges_count'] = $data['api_contest']['plannedChallengesCount'];

                $tParams['start_date'] = $apiContest->getStartDate()->format(\DateTime::ISO8601);
                $tParams['end_date'] = $apiContest->getEndDate()->format(\DateTime::ISO8601);
                
                //set api header
                $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $customerHeader[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
                }

                $contestseUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('contests') ;
                $curlService = $this->get('yon_user.data');

                $result = $curlService->curlPost($contestseUrl, $tParams, $customerHeader);

                //traitement des redirection si OK ou pas
                $response = json_decode($result);
                //var_dump($response);die;
                if($response && isset($response->id) && $response->id > 0){
                    if( (int)$data['to_belong_to_user'] > 0 ){
                        $apiContest = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:apiContest')->find($response->id);
                        $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($data['to_belong_to_user']);
                        $apiContest->setUser($authUser);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($apiContest);
                        $em->flush();
                    }
                    $this->get('session')->getFlashBag()->add('success', sprintf('un concours a été bien ajouté!.'));
                } else {
                   $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
                }
            }
            return $this->redirectToRoute('apicontest_index');
        }

        return $this->render('YonParisBundle:Apicontest:new.html.twig', array(
            'apiContest' => $apiContest,
            'form' => $form->createView(),
            'baseContest' => json_encode((array)$baseContest)
        ));
    }

    /**
     * Finds and displays a ApiContest entity.
     *
     */
    public function showAction(ApiContest $apiContest)
    {
        $deleteForm = $this->createDeleteForm($apiContest);

        return $this->render('YonParisBundle:Apicontest:show.html.twig', array(
            'apiContest' => $apiContest,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ApiContest entity.
     *
     */
    public function editAction(Request $request, ApiContest $apiContest)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        
        $routeName = $this->getRequest()->get('_route');
        $apiContestId = $apiContest->getId();
        
        $startDateApiContest    = $apiContest->getStartDate();
        $oApiContestchallenges  = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContestchallenge')->findBy(array(
                    'contest' => $apiContest->getId()
                ));
        
        $session->set('lastVisitePage', array($routeName,$apiContestId));
        $deleteForm = $this->createDeleteForm($apiContest);
        $editForm = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiContestType', $apiContest);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $data = $request->request->all();
            $curlService = $this->get('yon_user.data');
                 
            //prepare parametre pour envoyer vers api
            $tParams = array();
            $tParams['name'] = $data['api_contest']['name'];
            $tParams['description'] = $data['api_contest']['description'];
            $tParams['planned_challenges_count'] = $data['api_contest']['plannedChallengesCount'];
            
            $tParams['start_date'] = $apiContest->getStartDate()->format(\DateTime::ISO8601);
            $tParams['end_date'] = $apiContest->getEndDate()->format(\DateTime::ISO8601);
            
            //set api header
            $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
            if( (int)$data['to_belong_to_user'] > 0 ){
//                $customerHeader[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
                $tParams['user_id'] = $data['to_belong_to_user'];
            }
            
            $editContestseUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('contests').'/'. $apiContest->getId();            

//            var_dump($tParams);die;
            $result = $curlService->curlPatch($editContestseUrl, $tParams, $customerHeader);
            
            //traitement des redirection si OK ou pas
            $response = json_decode($result);
//            var_dump($response);die;
            if($response && isset($response->id) && $response->id > 0){
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $apiContest = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:apiContest')->find($response->id);
                    $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($data['to_belong_to_user']);
                    $apiContest->setUser($authUser);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($apiContest);
                    $em->flush();
                }
                
                $dateDeb        = $apiContest->getStartDate();            
                $d1             = strtotime($startDateApiContest->format('Y-m-d h:m'));
                $d2             = strtotime($dateDeb->format('Y-m-d h:m'));
                $datediff       = $d2 - $d1;
                $nbDayBetweenTwoDate = floor($datediff / (60 * 60 * 24));
                
                foreach($oApiContestchallenges as $resChallenge){
                    $tParamsChallengeEdit = array();
                    $start_date = $resChallenge->getChallenge()->getStartDate();
                    /*$startDate = clone $apiContest->getStartDate();
                    $endDate   = clone $apiContest->getEndDate();*/
                    $startDate = clone $resChallenge->getChallenge()->getStartDate();
                    $endDate   = clone $resChallenge->getChallenge()->getEndDate();
                    /*$tParamsChallengeEdit['start_date']       = $startDate->format(\DateTime::ISO8601);
                    $tParamsChallengeEdit['end_date']         = $endDate->format(\DateTime::ISO8601);*/
                    
                    if($nbDayBetweenTwoDate > 0 ){
                        $tParamsChallengeEdit['start_date']         = $startDate->add(new \DateInterval('P'.$nbDayBetweenTwoDate.'D'))->format(\DateTime::ISO8601);
                        $tParamsChallengeEdit['end_date']           = $endDate->add(new \DateInterval('P'.$nbDayBetweenTwoDate.'D'))->format(\DateTime::ISO8601);
                    }else{
                        $tParamsChallengeEdit['start_date']         = $startDate->format(\DateTime::ISO8601);
                        $tParamsChallengeEdit['end_date']           = $endDate->format(\DateTime::ISO8601);
                    }
                    $tParamsChallengeEdit['hashtag']                = $resChallenge->getChallenge()->getHashtag()->getTag();
                    
                    $editChallengeUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges').'/'. $resChallenge->getChallenge()->getId();
                    $customerHeader = array('Authorization: '. $session->get ( 'yon_token'));
                    if( (int)$data['to_belong_to_user'] > 0  ){
                        $customerHeader[] = 'X-YESorNO-UserID: '.$data['to_belong_to_user'];
                    }
                    $result = $curlService->curlPatch($editChallengeUrl, $tParamsChallengeEdit, $customerHeader);
                    //var_dump($tParamsChallengeEdit);die;
                }
                
               $this->get('session')->getFlashBag()->add('success', sprintf('le concours a été bien modifié!.'));
            } else {
               $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
            }
            
            return $this->redirectToRoute('apicontest_index');
        }

        return $this->render('YonParisBundle:Apicontest:edit.html.twig', array(
            'apiContest' => $apiContest,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ApiContest entity.
     *
     */
    public function deleteAction(Request $request, ApiContest $apiContest)
    {
        $session = $request->getSession ();
        
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        $form = $this->createDeleteForm($apiContest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($apiContest);
            $em->flush();
        }

        return $this->redirectToRoute('apicontest_index');
    }
    
    /**
     * Delete ajax contest 
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAjaxAction(Request $request)
    {
        $session = $request->getSession ();
        $curlService = $this->get('yon_user.data');
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        
        $data = $request->request->all();
       
        if( !(isset($data['contestId'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'messate' => 'parameter missing id')); 
        }
        
        try{
            $delContestUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('contests').'/'. $data['contestId'];
        
            $custHeaderContent = array('Authorization: '. $session->get ( 'yon_token'));


            $result = $curlService->curlDelete($delContestUrl, $custHeaderContent);

            $response = json_decode($result);
            $this->get('session')->getFlashBag()->add('success', sprintf('le concours a été bien supprimé!.'));
        } catch (Exception $ex) {
            
            return new JsonResponse(array('code' => 'eroor', 'message' => $ex->getMessage())); 
            
        }
        
        return new JsonResponse(array('code' => 'success'));
    }

    /**
     * Creates a form to delete a ApiContest entity.
     *
     * @param ApiContest $apiContest The ApiContest entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApiContest $apiContest)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('apicontest_delete', array('id' => $apiContest->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
