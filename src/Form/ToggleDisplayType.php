<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleDisplayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('toggleOn', SubmitType::class, [
                'label' => 'Turn Display ON',
                'attr' => ['class' => 'btn btn-success']
            ])
            ->add('toggleOff', SubmitType::class, [
                'label' => 'Turn Display OFF',
                'attr' => ['class' => 'btn btn-danger']
            ]);
    }
}
