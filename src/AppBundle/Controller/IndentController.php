<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Indent;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Indent controller.
 *
 * @Route("indent")
 */
class IndentController extends Controller
{
    /**
     * Lists all indent entities.
     *
     * @Route("/", name="indent_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $indents = $em->getRepository('AppBundle:Indent')->findBy([], ['id' => 'desc']);

        return $this->render('indent/index.html.twig', array(
            'indents' => $indents
        ));
    }

    /**
     * Creates a new indent entity.
     *
     * @Route("/new", name="indent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $indent = new Indent();
        $form = $this->createForm('AppBundle\Form\IndentType', $indent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($indent);
            $em->flush();

            return $this->redirectToRoute('indent_show', array('id' => $indent->getId()));
        }

        return $this->render('indent/new.html.twig', array(
            'indent' => $indent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a indent entity.
     *
     * @Route("/{id}", name="indent_show")
     * @Method("GET")
     */
    public function showAction(Indent $indent)
    {
        $deleteForm = $this->createDeleteForm($indent);

        $em = $this->getDoctrine()->getManager();
        // get product
        $product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $indent->getProductId()));

        // get distributor name
        $distributor = $em->getRepository('AppBundle:Distributor')
            ->findOneBy(array('id' => $product->getDistributor()));

        return $this->render('indent/show.html.twig', array(
            'distributorName' => $distributor->getName(),
            'productName' => $product->getName(),
            'indent' => $indent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing indent entity.
     *
     * @Route("/{id}/edit", name="indent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Indent $indent)
    {
        //$em = $this->getDoctrine()->getManager();
        // get product
        //$product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $indent->getProductId()));

        $deleteForm = $this->createDeleteForm($indent);
        $editForm = $this->createForm('AppBundle\Form\IndentType', $indent, array(
            'productId' => $indent->getProduct()->getId()
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('indent_edit', array('id' => $indent->getId()));
        }

        return $this->render('indent/edit.html.twig', array(
            'indent' => $indent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a indent entity.
     *
     * @Route("/{id}", name="indent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Indent $indent)
    {
        $form = $this->createDeleteForm($indent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($indent);
            $em->flush();
        }

        return $this->redirectToRoute('indent_index');
    }

    /**
     * Creates a form to delete a indent entity.
     *
     * @param Indent $indent The indent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Indent $indent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('indent_delete', array('id' => $indent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
