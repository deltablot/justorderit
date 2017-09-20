<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // THE SEARCH FORM
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('query', TextType::class, array(
                'label' => false,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 3)),
                ),
             ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM AppBundle:Product p WHERE p.name LIKE :query OR p.reference LIKE :query'
            )
                ->setParameter('query', '%' . $form->getData()['query'] . '%');
            $iterableResult = $query->iterate();
            return $this->render('product/search.html.twig', array(
                'products' => $iterableResult,
            ));
        }

        // THE DEFAULT PAGE
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
