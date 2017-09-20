<?php

namespace FT\ProjetStageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
            ->add('nom', TextType::class,
                array('label' => 'Nom', 'required' => true, 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'placeholder' => 'Taffaneau')))
            ->add('prenom', TextType::class,
                array('label' => 'Prénom', 'required' => true, 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'placeholder' => 'Florian')))
            ->add('username', TextType::class,
                array('label' => 'Pseudo', 'required' => true, 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'placeholder' => 'Altoros')))
            ->add('telephone', TextType::class,
                array('label' => 'Téléphone', 'required' => false, 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'placeholder' => '06/88/49/43/58')))
            ->add('email', EmailType::class,
                array('label' => 'Email', 'required' => true, 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'placeholder' => 'florian.taffaneau@laposte.net')))
            ->add('password', PasswordType::class,
                array('label' => 'Mot de passe', 'required' => true, 'data' => 'etudiant', 'label_attr' =>
                    array('class' => 'form-control'), 'attr' =>
                    array('class' => 'form-control', 'id' => 'password', 'placeholder' => 'mdp123')))
            ->add('save', SubmitType::class,
                array('attr' =>
                    array('class' => 'login-button')));
        //<button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
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
