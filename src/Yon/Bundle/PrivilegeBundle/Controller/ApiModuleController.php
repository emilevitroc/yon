<?php

namespace Yon\Bundle\PrivilegeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Yon\Bundle\PrivilegeBundle\Entity\ApiModule;
use Yon\Bundle\PrivilegeBundle\Form\ApiModuleType;

/**
 * ApiModule controller.
 *
 */
class ApiModuleController extends Controller
{
    /**
     * Lists all ApiModule entities.
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

        $apiModules = $em->getRepository('YonPrivilegeBundle:ApiModule')->findAll();

        return $this->render('YonPrivilegeBundle:apimodule:index.html.twig', array(
            'apiModules' => $apiModules,
        ));
    }

    /**
     * Creates a new ApiModule entity.
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
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
        $apiModule = new ApiModule();
        $form = $this->createForm('Yon\Bundle\PrivilegeBundle\Form\ApiModuleType', $apiModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiModule);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('un module a été crée!.'));
            return $this->redirectToRoute('apimodule_index');
        }

        return $this->render('YonPrivilegeBundle:apimodule:new.html.twig', array(
            'apiModule' => $apiModule,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ApiModule entity.
     *
     */
    public function showAction(ApiModule $apiModule)
    {
        $deleteForm = $this->createDeleteForm($apiModule);

        return $this->render('YonPrivilegeBundle:apimodule:show.html.twig', array(
            'apiModule' => $apiModule,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ApiModule entity.
     *
     */
    public function editAction(Request $request, ApiModule $apiModule)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
        $deleteForm = $this->createDeleteForm($apiModule);
        $editForm = $this->createForm('Yon\Bundle\PrivilegeBundle\Form\ApiModuleType', $apiModule);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiModule);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', sprintf('un module a été enregistrée!.'));
            return $this->redirectToRoute('apimodule_index');
        }

        return $this->render('YonPrivilegeBundle:apimodule:edit.html.twig', array(
            'apiModule' => $apiModule,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ApiModule entity.
     *
     */
    public function deleteAction(Request $request, ApiModule $apiModule)
    {
        $form = $this->createDeleteForm($apiModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($apiModule);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', sprintf('un module a été supprimée!.'));
        }
        
        return $this->redirectToRoute('apimodule_index');
    }

    /**
     * Creates a form to delete a ApiModule entity.
     *
     * @param ApiModule $apiModule The ApiModule entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApiModule $apiModule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('apimodule_delete', array('id' => $apiModule->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all ApiModule entities for grille form.
     *
     */
    public function grilleAction(Request $request)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        if($session->get ( 'privileges') == '' || ( $session->get ( 'privileges') != 'all' && !in_array($this->container->get('request')->get('_route'), explode(',', $session->get ( 'privileges')))) ){
            throw new AccessDeniedHttpException ();
        }
        
        $em = $this->getDoctrine()->getManager();
        
        if ($request->isMethod("POST")) {
            $data = $request->request->all();
            $tFeatures = $request->request->get('feature');
            foreach ($tFeatures as $id => $tfeature){
                $apiFeature = $em->getRepository('YonPrivilegeBundle:ApiFeature')->find($id);
                
                if(isset($tfeature['isSuperAdmin'])){
                    $apiFeature->setIsSuperAdmin(1);
                } else {
                    $apiFeature->setIsSuperAdmin(0);
                }
                if(isset($tfeature['isAdminIntern'])){
                    $apiFeature->setIsAdminIntern(1);
                } else {
                    $apiFeature->setIsAdminIntern(0);
                }
                if(isset($tfeature['isAdminExtern'])){
                    $apiFeature->setIsAdminExtern(1);
                } else {
                    $apiFeature->setIsAdminExtern(0);
                }
                if(isset($tfeature['isPartenaireCommercial'])){
                    $apiFeature->setIsPartenaireCommercial(1);
                } else {
                    $apiFeature->setIsPartenaireCommercial(0);
                }
                
                $em->persist($apiFeature);
                $em->flush();
                
            }
            
            $this->get('session')->getFlashBag()->add('success', sprintf('la grille a été enregistré!.'));
            return $this->redirectToRoute('apimodule_grille');
        }
        
        $query = $em->getRepository('YonPrivilegeBundle:ApiFeature')->createQueryBuilder('pf')
            ->leftJoin('pf.apiModule', 'pm')
                ->orderBy('pm.id', 'asc');
 
//        $apiFeatures = $em->getRepository('YonPrivilegeBundle:ApiFeature')->findAll();

        return $this->render('YonPrivilegeBundle:apimodule:grille.html.twig', array(
            'apiFeatures' => $query->getQuery()->getResult(),
        ));
    }
}
