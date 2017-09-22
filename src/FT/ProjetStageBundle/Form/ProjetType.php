<?php

namespace FT\ProjetStageBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'label' => 'Titre', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control', 'placeholder' => 'Mon titre')))
            ->add('dateLimite', DateType::class, array(
                'label' => 'Date limite de rendu', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control')))
            ->add('sujet', TextType::class, array(
                'label' => 'Sujet', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'Mon sujet')))
            ->add('sessionFormation', EntityType::class, array(
                'class' => 'FTProjetStageBundle:SessionFormation',
                'choice_label' => 'nom',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'label' => 'Session',
                'label_attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'login-button'
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FT\ProjetStageBundle\Entity\Projet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ft_projetstagebundle_projet';
    }
}
