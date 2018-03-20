<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('reference')->add('price')->add('currency');
        $builder->add('distributor', EntityType::class, array(
            'class' => 'AppBundle:Distributor',
            'choice_label' => 'name',
            'query_builder' => function ($er) {
                return $er->createQueryBuilder('d')
                    ->orderBy('d.name', 'ASC');
            },
        ));
        // add a field for the quote
        $builder->add('quote', FileType::class, array(
            'label' => 'Quote (PDF file)',
            'required' => false
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }
}
