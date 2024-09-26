<?php

declare(strict_types=1);

namespace App\Clock;

use App\Discography\Content\Lyric\LyricService;
use App\Entity\Lyric;

class LyricPicker
{

    public function __construct(private readonly LyricService $lyricService)
    {
    }

    public function getRandomLyricLine(): Lyric
    {
        $lyric = $this->lyricService->getRandomLyric();
    }
}
