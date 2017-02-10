<?php

namespace Yon\Bundle\ParisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Yon\Bundle\ParisBundle\Entity\ApiContest;
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
        
        $apiContest = new ApiContest();
        $form = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiContestType', $apiContest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $request->request->all();
            
            //prepare parametre pour envoyer vers api
            $tParams = array();
            $tParams['name'] = str_replace(array(' ', '#'), array('',''), $data['api_contest']['name']);
//            $tParams['hashtag_id'] = str_replace(array(' ', '#'), array('',''), $data['api_contest']['name']);
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
                $this->get('session')->getFlashBag()->add('success', sprintf('un concours a été bien ajouté!.'));
            } else {
               $this->get('session')->getFlashBag()->add('error', sprintf($response->message));
            }
            
            return $this->redirectToRoute('apicontest_index');
        }

        return $this->render('YonParisBundle:Apicontest:new.html.twig', array(
            'apiContest' => $apiContest,
            'form' => $form->createView(),
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
        
        $session->set('lastVisitePage', array($routeName,$apiContestId));
        $deleteForm = $this->createDeleteForm($apiContest);
        $editForm = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiContestType', $apiContest);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $data = $request->request->all();
            
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
            $curlService = $this->get('yon_user.data');
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
