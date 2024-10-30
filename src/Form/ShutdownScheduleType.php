<?php

namespace App\Form;

use App\Entity\ScheduleList;
use App\Entity\ShutdownSchedule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShutdownScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shutdownTime', TimeType::class, [
                'label' => 'Shutdown Time',
            ])
            ->add('restartTime', TimeType::class, [
                'label' => 'Restart Time',
            ])
            ->add('scheduleList', EntityType::class, [
                'class' => ScheduleList::class,
                'label' => 'Schedule List',
                'choice_label' => 'identifier',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Shutdown Schedule',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShutdownSchedule::class,
        ]);
    }
}
