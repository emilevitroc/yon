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
        ));
    }
    
    public function parisListAjaxAction(Request $request)
    {
        
        $filters  = $this->getFilters($request);
        $sortings = $this->getSortings($request, array(
            'p.id',
            'p.title',
            'pu.username',
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
        if(isset($coucoursId) && !empty($coucoursId)){
            $options['coucoursId'] = $coucoursId;
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
            
            $em->persist($contestChallenge);
            $em->flush();
            
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
    public function newWithAPIAction(Request $request)
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
            
            $data = $request->request->all();
            
            //prepare parametre pour envoyer vers api
            $tParams = array();
            $tParams['title'] = $data['api_challenge']['title'];
            $tParams['color'] = $data['api_challenge']['color'];
            
            if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
                $tParams['hashtag'] =  $data['api_challenge']['hashtag_user'];
            }
            
//            $tParams['time_to_end'] = floatval($data['api_challenge']['time_to_end']);
            $tParams['start_date'] = $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
            $tParams['end_date'] = $apiChallenge->getEndDate()->format(\DateTime::ISO8601);
//            $tParams['delayed_result'] = $data['api_challenge']['delayed_result'];
            $tParams['draft'] = 1;
//            echo $apiChallenge->getStartDate()->format(\DateTime::ISO8601);
           // var_dump( $tParams);
            $challengeUrl = $this->container->getParameter('api_url').''.$this->container->getParameter('challenges') ;
            
            $curlService = $this->get('yon_user.data');
        
            $result = $curlService->curlPost($challengeUrl, $tParams);

            //traitement des redirection si OK ou pas
            $response = json_decode($result);
            if($response && isset($response->id) && $response->id > 0){
                $challengeId = $response->id;
                $oChallenge = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiChallenge')->find($challengeId);
                
                
            
                if( (int)$data['to_belong_to_user'] > 0 ){
                    $authUserId = $data['to_belong_to_user'];

                } else {
                    $currentAuthUser = $session->get ( 'user_infos');
                    $authUserId = $currentAuthUser->id;
                }
                $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
                $oChallenge->setUser($authUser);

                // durée
                //$duration = isset($data['api_challenge']['duration']) ? $data['api_challenge']['duration'] : 1;

                $concours = isset($data['api_challenge']['contest']) ? $data['api_challenge']['contest'] : null;

                if($concours){
                    $contestChallenge = new \Yon\Bundle\ParisBundle\Entity\ApiContestchallenge();

                    $oContest = $authUser = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find($concours);
                    $contestChallenge->setCreation(new \DateTime());
                    $contestChallenge->setContest($oContest);
                    $contestChallenge->setChallenge($oChallenge);

                }
                
                if( !(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '') ){
                    $oChallenge->setHashtag($apiChallenge->getHashtag());
                }

                /*
                $startDate1 = $apiChallenge->getStartDate();
                $startDate2 = $apiChallenge->getStartDate();
                $apiChallenge->setStartDate($startDate1);
                $endDate = clone $apiChallenge->getStartDate();
                $endDate = $endDate->add(new \DateInterval('PT'.$duration.'H'));
                $apiChallenge->setEndDate($endDate);
                */
                $em = $this->getDoctrine()->getManager();
                $em->persist($oChallenge);
                $em->flush();

                $em->persist($contestChallenge);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', sprintf('un paris a été bien ajouté!.'));
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

        return $this->render('YonParisBundle:Paris:new.html.twig', array(
            'apiChallenge' => $apiChallenge,
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
        
        $deleteForm = $this->createDeleteForm($apiChallenge);
        $editForm = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiChallengeType', $apiChallenge);
        foreach ( $apiChallenge->getContestChallenge() as $absensi  ){
            $editForm->get('contest')->setData($absensi->getContest());
        }
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            
            if( (int)$data['to_belong_to_user'] > 0 ){
                $authUserId = $data['to_belong_to_user'];
                
            } else {
                $currentAuthUser = $session->get ( 'user_infos');
                $authUserId = $currentAuthUser->id;
            }
            $authUser = $this->getDoctrine()->getManager()->getRepository('YonUserBundle:AuthUser')->find($authUserId);
            $apiChallenge->setUser($authUser);
            
            //create hashtag
             if(isset($data['api_challenge']['hashtag_user']) && $data['api_challenge']['hashtag_user'] != '' ){
                $newHashtag = new ApiHashtag();
                $newHashtag->setTag($data['api_challenge']['hashtag_user']);
                
                $em->persist($newHashtag);
                $em->flush();
                
                $apiChallenge->setHashtag($newHashtag);
            }
            
            $em->persist($apiChallenge);
            $em->flush();
            if(isset($data['api_challenge']['contest']) && $data['api_challenge']['contest'] != '' ){
                $contestId = $data['api_challenge']['contest'];
                $contest = $this->getDoctrine()->getManager()->getRepository('YonParisBundle:ApiContest')->find($contestId);
                $apiContestchallenge = new \Yon\Bundle\ParisBundle\Entity\ApiContestchallenge();
                $apiContestchallenge->setChallenge($apiChallenge);
                $apiContestchallenge->setContest($contest);
                
                $em->persist($apiContestchallenge);
                $em->flush();
                
            }

            $this->get('session')->getFlashBag()->add('success', sprintf('un paris a été bien modifié!.'));
            return $this->redirectToRoute('apichallenge_index');
        }

        return $this->render('YonParisBundle:Paris:edit.html.twig', array(
            'apiChallenge' => $apiChallenge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
    
    public function addChallengeToContestAjaxAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $data = $request->request->all();
        
        if( !(isset($data['challenge']) && isset($data['contest'])) ) {
            return new JsonResponse(array('code' => 'eroor', 'messate' => 'parameter missing challenge, contest')); 
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
}
