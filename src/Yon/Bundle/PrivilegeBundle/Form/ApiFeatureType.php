<?php

namespace Yon\Bundle\PrivilegeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiFeatureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
//            ->add('isSuperAdmin')
//            ->add('isAdminIntern')
//            ->add('isAdminExtern')
//            ->add('isPartenaireCommercial')
            ->add('apiModule','entity',array(
                'empty_value' => 'Définir un module',
                'label_attr' => $options ['label_attr'],
                'class' => 'YonPrivilegeBundle:ApiModule',
                'property' => 'title',
                'required' => true,
                'label' => 'Module',
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yon\Bundle\PrivilegeBundle\Entity\ApiFeature'
        ));
    }
}
