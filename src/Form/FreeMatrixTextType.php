<?php

namespace App\Form;

use App\Clock\LedMatrixDisplayMode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FreeMatrixTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Text:',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter text...',
                ],
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'Modus:',
                'choices' => LedMatrixDisplayMode::getChoices(),
                'data' => LedMatrixDisplayMode::PERMANENT,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Choose mode...',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
