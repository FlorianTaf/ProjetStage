<?php

namespace FT\ProjetStageBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projets', EntityType::class, array(
                'class' => 'FTProjetStageBundle:Projet',
                'choice_label' => 'titre',
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'label' => 'Projet',
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
            'data_class' => 'FT\ProjetStageBundle\Entity\Equipe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ft_projetstagebundle_equipe';
    }


}
