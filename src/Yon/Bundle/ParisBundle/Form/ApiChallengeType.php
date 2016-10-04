<?php

namespace Yon\Bundle\ParisBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yon\Bundle\ParisBundle\Entity\ApiChallenge;

class ApiChallengeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('creation', 'datetime')
            ->add('title', null, array(
                    'label' => 'Intitulé',
                )
            )
            ->add(
                'startDate', 'datetime', 
                array(
                    'widget' => 'single_text',
                    'label' => 'Horaire de début',
                    'format' => 'dd/MM/yyyy HH:mm'
                )
            )
            ->add('duration', 'choice', array(
                'mapped' => false,
                'label' => 'Durée (heures)',
                'required' => true,
                'label_attr' => $options ['label_attr'],
                'empty_value' => 'Définir la durée',
                'choices' => ApiChallenge::$DURATION
                )
            )
//            ->add('endDate', 'datetime')
            ->add('hashtag','entity',array(
                'empty_value' => 'Définir un hashtag',
                'label_attr' => $options ['label_attr'],
                'class' => 'YonParisBundle:ApiHashtag',
                'property' => 'tag',
                'required' => true,
                'label' => 'Hashtag',
                'query_builder' => function ($repository) use($options) {
                        return $repository->createQueryBuilder ( 'sh' )
                                ->andWhere('sh.visible = 1')
                                ->addOrderBy('sh.range', 'DESC');
                },
                )
            )
            ->add('contest','entity',array(
                'empty_value' => 'Appartenance à un concours',
                'label_attr' => $options ['label_attr'],
                'class' => 'YonParisBundle:ApiContest',
                'property' => 'name',
                'required' => false,
                'mapped' => false,
                'label' => 'Concours',
                )
            )
            ->add('color')
            ->add('result')
            ->add('betPrice', null, array(
                    'label' => 'Prix Paris',
                ))
            ->add('status', 'choice', array (
				'label' => 'Statut',
                                'empty_value' => 'Définir un statut',
				'label_attr' => $options ['label_attr'],
				'required' => true,
				'choices' => ApiChallenge::$STATUS 
            ))
            
//            ->add('user_id', null, array('mapped' => false))
//            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yon\Bundle\ParisBundle\Entity\ApiChallenge'
        ));
    }
}
