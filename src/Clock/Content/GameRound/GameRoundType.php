<?php

namespace App\Clock\Content\GameRound;

enum GameRoundType: string
{
    case TEXTER_BOTH = 'texter_both';
    case TEXTER_MUSIC = 'texter_music';
    case TEXTER_LYRIC = 'texter_lyric';
    case ALBUM = 'album';
}
