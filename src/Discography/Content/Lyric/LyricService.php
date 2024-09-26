<?php

declare(strict_types=1);

namespace App\Discography\Content\Lyric;

use App\Entity\Lyric;
use App\Entity\Song;
use Exception;

class LyricService
{
    public function __construct(readonly private LyricRepository $lyricRepository)
    {
    }

    /**
     * @param array<string> $rawLyrics
     */
    public function storeLyricsForSong(Song $song, array $rawLyrics): void
    {
        $lyrics = [];
        foreach ($rawLyrics as $rawLyric) {
            $lyric = new Lyric();
            $lyric->setSong($song);
            $lyric->setContent($rawLyric);

            $lyrics[] = $lyric;
        }

        $this->lyricRepository->persistLyrics($lyrics);
    }

    /**
     * @throws Exception
     */
    public function getRandomLyric(bool $excludeDuplicates = true): Lyric
    {
        $lyric = $this->lyricRepository->getRandomLyric($excludeDuplicates);
        if ($lyric === null) {
            throw new Exception('No lyric found');
        }

        return $lyric;
    }
}
