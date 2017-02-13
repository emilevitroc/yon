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
            /*->add('title', null, array(
                    'label' => 'Intitulé',
                )
            )*/
            ->add('duration', null, array(
                'mapped' => false,
                'label' => 'Durée (HH:mm)',
                'required' => false,
                'read_only' =>'true',
                'label_attr' => $options ['label_attr'],
                )
            )
            ->add(
                'startDate', 'datetime', 
                array(
                    'widget' => 'single_text',
                    'label' => 'Horaire de début',
                    'format' => 'dd/MM/yyyy HH:mm',
//                    'model_timezone' => 'UTC',
                    'view_timezone' => 'Europe/Paris'
                )
            )
            ->add(
                'endDate', 'datetime', 
                array(
                    'widget' => 'single_text',
                    'label' => 'Horaire de fin',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'view_timezone' => 'Europe/Paris'
                )
            )
//            ->add('endDate', 'datetime')
            ->add('choice_hashtag', 'choice', array(
                'label' => 'Choix hashtags',
                'choices' => array('trends' => 'Trending topics', 'manuel' => 'Manuel'),
                'expanded' => true,
                'multiple' => false,
                'mapped'   => false,
                'label_attr' => $options ['label_attr'],
            ))
            ->add('trendingTopics','entity',array(
                'mapped' => false,
                'empty_value' => 'Choisir un trending topic',
                'label_attr' => $options ['label_attr'],
                'class' => 'YonParisBundle:ApiFeaturedhashtag',
                'property' => 'hashtag.tag',
                'required' => false,
                'label' => 'Trending topics',
                'query_builder' => function ($repository) use($options) {
                        return $repository->createQueryBuilder ( 'sh' )
                                //->andWhere('sh.visible = 1')
                                ->addOrderBy('sh.id', 'DESC');
                },
                )
            )
            ->add('hashtag_user', null, array(
                'mapped' => false,
                'label' => 'Votre propre hashtag',
                'required' => false,
                'label_attr' => $options ['label_attr'],
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
            ->add('color', 'choice', array(
                'label' => 'Couleur',
                'required' => true,
                'label_attr' => $options ['label_attr'],
                'empty_value' => 'Définir la couleur',
                'choices' => ApiChallenge::$COLOR
                )
            )
            ->add('result', 'choice', array(
                'label' => 'Réponse',
                'required' => false,
                'label_attr' => $options ['label_attr'],
                'empty_value' => 'Définir la réponse',
                'choices' => ApiChallenge::$RESULT
                )
            )
            ->add('betPrice', null, array(
                    'label' => 'Prix du paris',
                )
            )
            ->add('prize', null, array(
                    'label' => 'Prize',
                )
            )
            ->add('status', 'choice', array (
                'label' => 'Brouillons',
                'label_attr' => $options ['label_attr'],
                'required' => false,
                'choices' => ApiChallenge::$DRAFT_CHOICE 
                )
            )
            ->add('alertMessage', 'textarea', array(
                    'label' => 'Popup message',
                    'required' => false,
                )
            )
//            ->add('status', 'choice', array (
//                'label' => 'Statut',
//                'empty_value' => 'Définir un statut',
//                'label_attr' => $options ['label_attr'],
//                'required' => false,
//                'choices' => ApiChallenge::$STATUS 
//                )
//            )
            
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
            'data_class' => 'Yon\Bundle\ParisBundle\Entity\ApiChallenge',
            'allow_extra_fields' => true,
        ));
    }
}
