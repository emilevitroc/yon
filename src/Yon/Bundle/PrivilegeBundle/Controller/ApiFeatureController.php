<?php

namespace Yon\Bundle\PrivilegeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Yon\Bundle\PrivilegeBundle\Entity\ApiFeature;
use Yon\Bundle\PrivilegeBundle\Form\ApiFeatureType;

/**
 * ApiFeature controller.
 *
 */
class ApiFeatureController extends Controller
{
    /**
     * Lists all ApiFeature entities.
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

        $apiFeatures = $em->getRepository('YonPrivilegeBundle:ApiFeature')->findAll();

        return $this->render('YonPrivilegeBundle:apifeature:index.html.twig', array(
            'apiFeatures' => $apiFeatures,
        ));
    }

    /**
     * Creates a new ApiFeature entity.
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
        
        $apiFeature = new ApiFeature();
        $form = $this->createForm('Yon\Bundle\PrivilegeBundle\Form\ApiFeatureType', $apiFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiFeature);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('une fonctionnalité a été crée!.'));
            return $this->redirectToRoute('apifeature_index');
        }

        return $this->render('YonPrivilegeBundle:apifeature:new.html.twig', array(
            'apiFeature' => $apiFeature,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ApiFeature entity.
     *
     */
    public function showAction(ApiFeature $apiFeature)
    {
        $deleteForm = $this->createDeleteForm($apiFeature);

        return $this->render('YonPrivilegeBundle:apifeature:show.html.twig', array(
            'apiFeature' => $apiFeature,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ApiFeature entity.
     *
     */
    public function editAction(Request $request, ApiFeature $apiFeature)
    {
        $session = $request->getSession ();
        if(!$session->get ( 'yon_token')){
            $url = $this->container->get('router')->generate('yon_user_login');
            $response = new RedirectResponse($url);
            return $response;
        }
        
        $deleteForm = $this->createDeleteForm($apiFeature);
        $editForm = $this->createForm('Yon\Bundle\PrivilegeBundle\Form\ApiFeatureType', $apiFeature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiFeature);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', sprintf('une fonctionnalité a été enregistrée!.'));
            return $this->redirectToRoute('apifeature_index');
        }

        return $this->render('YonPrivilegeBundle:apifeature:edit.html.twig', array(
            'apiFeature' => $apiFeature,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ApiFeature entity.
     *
     */
    public function deleteAction(Request $request, ApiFeature $apiFeature)
    {
        $form = $this->createDeleteForm($apiFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($apiFeature);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', sprintf('une fonctionnalité a été supprimée!.'));
        }

        return $this->redirectToRoute('apifeature_index');
    }

    /**
     * Creates a form to delete a ApiFeature entity.
     *
     * @param ApiFeature $apiFeature The ApiFeature entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApiFeature $apiFeature)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('apifeature_delete', array('id' => $apiFeature->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
