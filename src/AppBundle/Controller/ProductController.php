<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Indent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm('AppBundle\Form\ProductType', $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $product->getQuote();
            // Generate a unique name for the file before saving it
            $fileName = hash('sha512', uniqid(rand(), true)) . '.' . $file->guessExtension();
            $folder = substr($fileName, 0, 3);
            // Move the file to the directory where quotes are stored
            $file->move(
                $this->getParameter('quotes_directory') . '/' . $folder,
                $fileName
            );
            // Update the 'quote' property to store the PDF file name
            // instead of its contents
            $product->setQuote($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'Product successfully added.'
            );

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Order this product.
     *
     * @Route("/{id}/order", name="product_order")
     * @Method({"GET", "POST"})
     */
    public function orderAction(Request $request, Product $product)
    {
        $indent = new Indent();

        $orderForm = $this->createForm('AppBundle\Form\IndentType', $indent, array(
            'productId' => $product->getId()
        ));

        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // set indent->product. This took me a long time to figure out…
            $indent->setProduct($product);
            $em->persist($indent);
            $em->flush();

            $this->addFlash(
                'notice',
                'Order is passed. Bisous bisous <3'
            );
            return $this->redirectToRoute('indent_index');
        }

        return $this->render('product/order.html.twig', array(
            'product' => $product,
            'order_form' => $orderForm->createView()
        ));
    }
    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        // fix the quote field
        if ($product->getQuote()) {
            $folder = substr($product->getQuote(), 0, 3);
            $product->setQuote(
                new File($this->getParameter('quotes_directory') . '/' . $folder . '/' . $product->getQuote())
            );
        }

        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $product->getQuote();
            // Generate a unique name for the file before saving it
            $fileName = hash('sha512', uniqid(rand(), true)) . '.' . $file->guessExtension();
            $folder = substr($fileName, 0, 3);
            // Move the file to the directory where quotes are stored
            $file->move(
                $this->getParameter('quotes_directory') . '/' . $folder,
                $fileName
            );
            // Update the 'quote' property to store the PDF file name
            // instead of its contents
            $product->setQuote($fileName);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Changes have been saved.'
            );
            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
            $this->addFlash(
                'notice',
                'Product deleted.'
            );
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
