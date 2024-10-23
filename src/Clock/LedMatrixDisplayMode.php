<?php

namespace App\Clock;
enum LedMatrixDisplayMode: string
{
    case OFF = 'off';
    case PERMANENT = 'permanent';
    case RUNNING = 'running';

    public static function getChoices(): array
    {
        return [
            'Off' => self::OFF,
            'Permanent' => self::PERMANENT,
            'Laufend' => self::RUNNING
        ];
    }
}
