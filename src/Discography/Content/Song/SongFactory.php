<?php

declare(strict_types=1);

namespace App\Discography\Content\Song;

use App\Discography\Content\Song\Data\SongData;
use App\Entity\Song;

class SongFactory
{
    public function createByData(SongData $data): Song
    {
        $song = $this->createNewInstance();
        $this->mapData($data, $song);
        return $song;
    }

    public function mapData(SongData $data, Song $song): Song
    {
        $song->setName($data->getName());

        return $song;
    }

    private function createNewInstance(): Song
    {
        return new Song();
    }
}
