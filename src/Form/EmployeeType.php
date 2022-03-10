<?php

namespace App\Form;

use App\Entity\Jobs;
use App\Entity\Employees;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as Type;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', Type\TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lastName', Type\TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('cost', Type\IntegerType::class, [
                'label' => 'Coût journalier',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\Positive()
                ]
            ])
            ->add('hireDate', Type\DateType::class, [
                'label' => 'Date d\'embauche',
            ])
            ->add('job', EntityType::class, [
                'label' => 'Métier',
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => Jobs::class,
                'choice_label' => 'label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employees::class,
            'required' => true
        ]);
    }
}
