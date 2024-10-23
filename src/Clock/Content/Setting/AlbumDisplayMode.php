<?php

namespace App\Clock\Content\Setting;

enum AlbumDisplayMode: string
{
    case CAROUSEL = 'carousel';
    case SPLIT = 'split';
    case LOOSE = 'loose';
    case WIN = 'win';

    public static function getChoices(): array
    {
        return [
            'Carousel' => self::CAROUSEL,
            'Split' => self::SPLIT,
            'Loose' => self::LOOSE,
            'Win' => self::WIN,
        ];
    }
}
