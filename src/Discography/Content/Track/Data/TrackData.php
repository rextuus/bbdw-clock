<?php

declare(strict_types=1);

namespace App\Discography\Content\Track\Data;

use App\Entity\Album;
use App\Entity\Song;
use App\Entity\Track;

class TrackData
{
    private int $number;

    private Album $album;

    private Song $song;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): TrackData
    {
        $this->number = $number;
        return $this;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): TrackData
    {
        $this->album = $album;
        return $this;
    }

    public function getSong(): Song
    {
        return $this->song;
    }

    public function setSong(Song $song): TrackData
    {
        $this->song = $song;
        return $this;
    }

    public static function initFromEntity(Track $track): TrackData
    {
        $trackData = new self();
        $trackData->setNumber($track->getNumber());
        $trackData->setSong($track->getSong());
        $trackData->setAlbum($track->getAlbum());

        return $trackData;
    }
}
