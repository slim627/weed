<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\BooleanChoiceType;
use CommonBundle\Form\Type\ContactMethodType;
use CommonBundle\Form\Type\DateTimePickerType;
use CommonBundle\Utils\BooleanChoice;
use CommonBundle\Utils\ContactMethod;
use CommonBundle\Utils\Status;
use PatientBundle\Entity\Complaint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplaintType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => 'PatientBundle\Entity\Patient'
            ])
            ->add('productName', TextType::class)
            ->add('receivedBy', EntityType::class, [
                'class' => 'StaffBundle\Entity\Employee',
                'data'  => $this->getUser(),
            ])
            ->add('sourceOfComplaint', ComplaintSourceType::class)
            ->add('lotBatchNumber', TextType::class)
            ->add('dueDate', DateTimePickerType::class)
            ->add('packageSize', IntegerType::class)
            ->add('title', TextType::class)
            ->add('dateSampleReceived', DateTimePickerType::class)
            ->add('priority', ComplaintPriorityType::class)
            ->add('sampleAvailable', BooleanChoiceType::class)
            ->add('quantityReceived', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('preferredContactMethod', ContactMethodType::class)
            ->add('assignedTo', EntityType::class, [
                'class' => 'StaffBundle\Entity\Employee'
            ])
            ->add('status', ComplaintStatusType::class)
            ->add('note', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PatientBundle\Entity\Complaint',
            'csrf_protection' => false,
        ]);
    }

    public function getUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();

        return $token->getUser();
    }
}