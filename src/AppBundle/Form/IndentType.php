<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IndentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity');
        $builder->add('user');

        // add hidden date field
        $builder->add(
            'order_time',
            DateTimeType::class,
            array(
                'data' => new \DateTime("now"),
                'attr' => array('style' => 'display:none;'),
                'label' => false
            )
        );

        // add hidden product_id field
        $builder->add('product_id', IntegerType::class, array(
            'data' => $options['productId'],
            'label' => false,
            'attr' => array('style' => 'display:none;'),

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Indent'
        ));
        $resolver->setRequired('productId');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_indent';
    }
}
