<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Distributor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Distributor controller.
 *
 * @Route("distributor")
 */
class DistributorController extends Controller
{
    /**
     * Lists all distributor entities.
     *
     * @Route("/", name="distributor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $distributors = $em->getRepository('AppBundle:Distributor')->findAll();

        return $this->render('distributor/index.html.twig', array(
            'distributors' => $distributors,
        ));
    }

    /**
     * Creates a new distributor entity.
     *
     * @Route("/new", name="distributor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $distributor = new Distributor();
        $form = $this->createForm('AppBundle\Form\DistributorType', $distributor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($distributor);
            $em->flush();

            $this->addFlash(
                'notice',
                'Distributor successfully added.'
            );

            return $this->redirectToRoute('distributor_show', array('id' => $distributor->getId()));
        }

        return $this->render('distributor/new.html.twig', array(
            'distributor' => $distributor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a distributor entity.
     *
     * @Route("/{id}", name="distributor_show")
     * @Method("GET")
     */
    public function showAction(Distributor $distributor)
    {
        $deleteForm = $this->createDeleteForm($distributor);

        return $this->render('distributor/show.html.twig', array(
            'distributor' => $distributor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing distributor entity.
     *
     * @Route("/{id}/edit", name="distributor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Distributor $distributor)
    {
        $deleteForm = $this->createDeleteForm($distributor);
        $editForm = $this->createForm('AppBundle\Form\DistributorType', $distributor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Changes have been saved.'
            );

            return $this->redirectToRoute('distributor_edit', array('id' => $distributor->getId()));
        }

        return $this->render('distributor/edit.html.twig', array(
            'distributor' => $distributor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a distributor entity.
     *
     * @Route("/{id}", name="distributor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Distributor $distributor)
    {
        $form = $this->createDeleteForm($distributor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($distributor);
            $em->flush();
            $this->addFlash(
                'notice',
                'Distributor deleted.'
            );
        }

        return $this->redirectToRoute('distributor_index');
    }

    /**
     * Creates a form to delete a distributor entity.
     *
     * @param Distributor $distributor The distributor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Distributor $distributor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('distributor_delete', array('id' => $distributor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
