<?php

namespace Yon\Bundle\ParisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;
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
        
        //$apiChallenges = $em->getRepository('YonParisBundle:ApiChallenge')->findAll();
        return $this->render('YonParisBundle:Paris:index.html.twig');
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
        
        $nbParis         = $this->get('yon_paris.paris_manager')->getNbParisBy($options);
        $parisResult   = $this->get('yon_paris.paris_manager')->getParisBy($options, $filters, $sortings)->getResult();

        $content = $this->getDataJson(
            $request,
            $nbParis['nbParis'],
            $nbParis['nbParis'],
            $parisResult,
            'YonParisBundle:Paris:parisListAjax.json.twig'
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
            
            /*
            $startDate1 = $apiChallenge->getStartDate();
            $startDate2 = $apiChallenge->getStartDate();
            $apiChallenge->setStartDate($startDate1);
            $endDate = clone $apiChallenge->getStartDate();
            $endDate = $endDate->add(new \DateInterval('PT'.$duration.'H'));
            $apiChallenge->setEndDate($endDate);
            */
            $em = $this->getDoctrine()->getManager();
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
        $deleteForm = $this->createDeleteForm($apiChallenge);
        $editForm = $this->createForm('Yon\Bundle\ParisBundle\Form\ApiChallengeType', $apiChallenge);
        foreach ( $apiChallenge->getContestChallenge() as $absensi  ){
            $editForm->get('contest')->setData($absensi->getContest());
        }
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiChallenge);
            $em->flush();

            return $this->redirectToRoute('apichallenge_edit', array('id' => $apiChallenge->getId()));
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
}
