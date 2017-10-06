<?php

namespace FT\ProjetStageBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    /*
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    */

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Nom', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control','datas-id' => 'Nom', 'placeholder' => 'Taffaneau')))
            ->add('prenom', TextType::class, array(
                'label' => 'Prénom', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control','datas-id' => 'Prenom', 'placeholder' => 'Florian')))
            ->add('username', TextType::class, array(
                'label' => 'Pseudo', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control', 'datas-id' => 'Username', 'placeholder' => 'Altoros')))
            ->add('telephone', TextType::class, array(
                'label' => 'Téléphone', 'required' => false, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control','datas-id' => 'Telephone', 'placeholder' => '06/88/49/43/58')))
            ->add('email', EmailType::class, array(
                'label' => 'Email', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control', 'datas-id' => 'Email' , 'placeholder' => 'florian.taffaneau@laposte.net')))
            ->add('password', PasswordType::class, array(
                'label' => 'Mot de passe', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control', 'id' => 'password', 'placeholder' => 'mdp123')))
            ->add('role', EntityType::class, array(
                'class' => 'FTProjetStageBundle:Role',
                //'data' => $this->em->getReference('FTProjetStageBundle:Role', 1),
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('r')
                        ->where('r.name != :roleAdmin')->setParameter('roleAdmin', 'ROLE_ADMIN');
                },
                'choice_label' => 'name',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'Role',
                'label_attr' => array(
                    'class' => 'form-control',
                    'datas-id' => 'Role'
                ),
            ))
            ->add('file', FileType::class, array(
                'label' => 'Image', 'required' => false, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                        'class' => 'form-control','datas-id' => 'File', 'id' => 'image')))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'login-button')));
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
