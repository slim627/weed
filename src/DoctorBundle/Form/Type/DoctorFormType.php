<?php

namespace DoctorBundle\Form\Type;

use CommonBundle\Form\Type\DateTimePickerType;
use CommonBundle\Form\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phone', TextType::class)
            ->add('licenseNumber', TextType::class)
            ->add('licenseNumberVerified', CheckboxType::class)
            ->add('officeAddress', TextType::class)
            ->add('primaryCenter', TextType::class)
            ->add('designationDate', DateTimePickerType::class)
            ->add('recommendationsNotes', TextType::class)
            ->add('recommendationsVerified', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'DoctorBundle\Entity\Doctor',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'doctor';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}