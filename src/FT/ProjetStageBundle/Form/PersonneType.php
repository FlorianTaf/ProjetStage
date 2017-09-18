<?php

namespace FT\ProjetStageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label' => 'lastName', 'required' => true, 'attr' => array('class' => 'form-control', 'placeholder' => 'Taffaneau')))
            ->add('prenom', TextType::class, array('label' => 'firstName', 'required' => true, 'attr' => array('class' => 'form-control', 'placeholder' => 'Florian')))
            ->add('username', TextType::class, array('label' => 'userName', 'required' => true, 'attr' => array('class' => 'form-control', 'placeholder' => 'Altoros')))
            ->add('telephone', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)/*
            ->add('type', 'choice', array(
                'choices' => array('etudiant' => 'Étudiant', 'formateur' => 'Formateur'),
                'required' => true,
                'preferred_choices' => array('etudiant'),
                'empty_value' => 'Veuillez choisir un statut'
            ))
            */
        ->add('save', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FT\ProjetStageBundle\Entity\Personne'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ft_projetstagebundle_personne';
    }


}
