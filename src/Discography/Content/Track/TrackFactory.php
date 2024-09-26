<?php

declare(strict_types=1);

namespace App\Discography\Content\Track;

use App\Discography\Content\Track\Data\TrackData;
use App\Entity\Track;

class TrackFactory
{
    public function createByData(TrackData $data): Track
    {
        $track = $this->createNewInstance();
        $this->mapData($data, $track);
        return $track;
    }

    public function mapData(TrackData $data, Track $track): Track
    {
        $track->setSong($data->getSong());
        $track->setAlbum($data->getAlbum());
        $track->setNumber($data->getNumber());

        return $track;
    }

    private function createNewInstance(): Track
    {
        return new Track();
    }
}
