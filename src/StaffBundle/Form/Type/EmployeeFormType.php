<?php

namespace StaffBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeeFormType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $qb = $this->container
            ->get('doctrine')
            ->getManager()
            ->getRepository('StaffBundle:AccessLevel')
            ->createQueryBuilder('al')
            ->where('al.parent IS NULL')
        ;

        $builder
            ->add('supervisor', EntityType::class, [
                'class' => 'StaffBundle\Entity\Employee',
            ])
            ->add('accessLevel', EntityType::class, [
                'class' => 'StaffBundle\Entity\AccessLevel',
                'query_builder' => $qb,
            ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('company', TextType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'staff_employee';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}