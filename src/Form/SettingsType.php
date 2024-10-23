<?php

namespace App\Form;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\Data\SettingData;
use App\Clock\LedMatrixDisplayMode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ledMatrixDisplayIp')
            ->add('currentGameMode', ChoiceType::class, [
                'choices' => GameRoundType::getChoices(),
                'choice_value' => fn(?GameRoundType $choice) => $choice?->value,
                'choice_label' => fn(GameRoundType $choice) => $choice->name,
            ])
            ->add('gamesPerDayLimit')
            ->add('albumDisplayMode', ChoiceType::class, [
                'choices' => AlbumDisplayMode::getChoices(),
                'choice_value' => fn(?AlbumDisplayMode $choice) => $choice?->value,
                'choice_label' => fn(AlbumDisplayMode $choice) => $choice->name,
            ])
            ->add('ledMatrixDisplayMode', ChoiceType::class, [
                'choices' => LedMatrixDisplayMode::getChoices(),
                'choice_value' => fn(?LedMatrixDisplayMode $choice) => $choice?->value,
                'choice_label' => fn(LedMatrixDisplayMode $choice) => $choice->name,
            ])
            ->add('forceNextGameInstantly', CheckboxType::class, [
                'required' => false,
            ])
            ->add('fontColor', ColorType::class, [
                'label' => 'Font Color'
            ])
            ->add('Save', SubmitType::class);

        $builder->get('fontColor')
            ->addModelTransformer(new CallbackTransformer(
                function ($fontColorArray) {
                    // Convert array to hex string for the color picker, with default values
                    if (is_array($fontColorArray) && isset($fontColorArray['r'], $fontColorArray['g'], $fontColorArray['b'])) {
                        return sprintf("#%02x%02x%02x", $fontColorArray['r'], $fontColorArray['g'], $fontColorArray['b']);
                    }
                    return "#000000";
                },
                function ($fontColorString) {
                    // Convert hex string to array with default values
                    if (is_string($fontColorString) && preg_match('/^#(?<r>[0-9a-f]{2})(?<g>[0-9a-f]{2})(?<b>[0-9a-f]{2})$/i', $fontColorString, $matches)) {
                        return [
                            'r' => hexdec($matches['r']),
                            'g' => hexdec($matches['g']),
                            'b' => hexdec($matches['b']),
                        ];
                    }
                    return ['r' => 0, 'g' => 0, 'b' => 0];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SettingData::class,
        ]);
    }
}