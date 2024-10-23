<?php

namespace App\Clock\Content\GameRound;

enum GameRoundType: string
{
    case TEXTER_BOTH = 'texter_both';
    case TEXTER_MUSIC = 'texter_music';
    case TEXTER_LYRIC = 'texter_lyric';
    case ALBUM = 'album';

    public static function getChoices(): array
    {
        return [
            'Texter Both' => self::TEXTER_BOTH,
            'Texter Music' => self::TEXTER_MUSIC,
            'Texter Lyric' => self::TEXTER_LYRIC,
            'Album' => self::ALBUM,
        ];
    }
}
