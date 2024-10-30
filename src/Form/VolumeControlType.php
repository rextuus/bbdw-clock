<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolumeControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('volume', RangeType::class, [
                'attr' => ['min' => 0, 'max' => 100],
                'label' => 'Volume Level',
                'data' => $options['data']['volume'] ?? 50, // Default to 50 if not set
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => ['volume' => 50], // Default volume level
        ]);
    }
}
