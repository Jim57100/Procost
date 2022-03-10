<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Times;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Constraints;


class TimesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('times', NumberType::class, [
                'label' => 'Nombre de jours',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Constraints\NotNull(),
                    new Constraints\Range(
                        min: 0,
                        max: 365
                    )
                ]
            ])
            ->add('projects', EntityType::class, [
                'label' => 'Projet concerné',
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => Projects::class,
                'choice_label' => 'name',
                // 'query_builder' => function(ProjectsRepository $repo) {
                //     return $repo->createQueryBuilder('p')->orderBy('p.name', 'DESC');
                // },
                'constraints' => [
                    new Constraints\NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Times::class,
            'required' => true,
        ]);
    }
}
