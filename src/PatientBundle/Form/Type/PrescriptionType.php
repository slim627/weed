<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\BooleanChoiceType;
use CommonBundle\Form\Type\DateTimePickerType;
use CommonBundle\Utils\BooleanChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => 'PatientBundle\Entity\Patient',
            ])
            ->add('prescriptionStart', DateTimePickerType::class)
            ->add('prescriptionEnd', DateTimePickerType::class)
            ->add('cycleStartDate', DateTimePickerType::class)
            ->add('cycleEndDate', DateTimePickerType::class)
            ->add('doctor', EntityType::class, [
                'class' => 'DoctorBundle\Entity\Doctor',
            ])
            ->add('monthlyLimit', IntegerType::class)
            ->add('dailyLimit', IntegerType::class)
            ->add('prescriptionStatus', BooleanChoiceType::class)
            ->add('note', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PatientBundle\Entity\Prescription',
            'csrf_protection' => false,
        ]);
    }
}