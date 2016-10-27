<?php

namespace App\FormationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('required' => true, 'label' => 'first_name'))
            ->add('lastName', TextType::class, array('required' => true, 'label' => 'last_name'))
            ->add('cellphone', TextType::class, array('required' => false, 'label' => 'phone'))
            ->add('email', EmailType::class, array('required' => true, 'label' => 'email'))
            ->add('additionalInformation', TextareaType::class, array('required' => false, 'label' => 'additional_information'))
            ->add('knowledge', ChoiceType::class, array(
                'choices'     => array(
                    'internet'         => 'internet',
                    'facebook'         => 'facebook',
                    'pub_papier'       => 'pub_papier',
                    'bouche_a_oreille' => 'bouche_a_oreille',
                    'presse_ecrite'    => 'presse_ecrite',
                    'reseaux_sociaux'  => 'reseaux_sociaux',
                    'autre'            => 'autre',
                ),
                'required'    => false,
                'expanded'    => true,
                'multiple'    => false,
                'placeholder' => false,
                'choices_as_values' => true,
                'label' => 'knowledge',
            ))
            ->add('other', TextType::class, array('label' => 'autre'))
            ->add('Envoyer', SubmitType::class, array(
                'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
                'label' => 'validation_button'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FormationBundle\Entity\Contact',
            'csrf_protection' => false,
            'translation_domain' => 'contact',
        ));
    }
}