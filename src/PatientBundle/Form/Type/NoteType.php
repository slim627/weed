<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\DateTimePickerType;
use CommonBundle\Form\Type\TernaryChoiceType;
use CommonBundle\Utils\TernaryChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('patient', EntityType::class, [
                'class' => 'PatientBundle\Entity\Patient',
            ])
            ->add('complaint', EntityType::class, [
                'class' => 'PatientBundle\Entity\Complaint',
            ])
            ->add('actionType', TernaryChoiceType::class)
            ->add('accountPhone', TextType::class)
            ->add('contactPhone', TextType::class)
            ->add('accountStatus', TernaryChoiceType::class)
            ->add('assignedTo', EntityType::class, [
                'class' => 'StaffBundle\Entity\Employee'
            ])
            ->add('startTime', DateTimePickerType::class)
            ->add('endTime', DateTimePickerType::class)
            ->add('description', TextareaType::class)
            ->add('isComplete', CheckboxType::class)
            ->add('completeDate', DateTimePickerType::class)
            ->add('note', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PatientBundle\Entity\NoteAndTask',
            'csrf_protection' => false,
        ]);
    }
}