<?php
/**
 * Created by PhpStorm.
 * User: alex chistov
 * Date: 24.2.16
 * Time: 15.24
 */

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\BooleanChoiceType;
use CommonBundle\Form\Type\ContactMethodType;
use CommonBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('dateOfBirth', DateTimePickerType::class)
            ->add('healthNumber', TextType::class)
            ->add('phone', TextType::class)
            ->add('onlineAccount', BooleanChoiceType::class)
            ->add('mailingAddress', TextType::class)
            ->add('deliveryAddress', TextType::class)
            ->add('email', EmailType::class)
            ->add('preferredContactMethod', ContactMethodType::class)
            ->add('taxExemption', TextType::class)
            ->add('diagnosis', TextType::class)
            ->add('accountManager', EntityType::class, [
                'class' => 'StaffBundle\Entity\Employee',
            ])
            ->add('memberSince', DateTimePickerType::class)
            ->add('memberExpire', DateTimePickerType::class)
            ->add('doctor', EntityType::class, [
                'class' => 'DoctorBundle\Entity\Doctor',
            ])
            ->add('files', FileType::class, [
                'multiple' => true,
                'data_class' => null,
                'constraints' => new All(array(
                    'constraints' => array(
                        new FileConstraint([
                            'maxSize' => '32Mi',
                            'mimeTypes' => [
                                'text/plain',
                                'application/pdf', 'application/x-pdf',
                                'application/msword', 'application/rtf',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'image/gif', 'image/jpeg', 'image/png',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/zip', 'application/x-compressed-zip',
                            ],
                        ]),
                    ))),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PatientBundle\Entity\Patient',
            'csrf_protection' => false,
        ]);
    }
}