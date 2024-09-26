<?php

declare(strict_types=1);

namespace App\Discography\Content\Album;

use App\Discography\Content\Album\Data\AlbumData;
use App\Entity\Album;

class AlbumFactory
{
    public function createByData(AlbumData $data): Album
    {
        $album = $this->createNewInstance();
        $this->mapData($data, $album);
        return $album;
    }

    public function mapData(AlbumData $data, Album $album): Album
    {
        $album->setName($data->getName());
        $album->setType($data->getReleaseType());
        $album->setYear($data->getYear());
        $album->setAmountOfTracks($data->getAmountOfTracks());
        $album->setCoverImageUrl($data->getCoverImageUrl());
        $album->setDetailUrl($data->getDetailUrl());
        $album->setFile($data->getCover());

        if ($data->getRawTrackData() !== null){
            $album->setRawTrackData($data->getRawTrackData());
        }

        return $album;
    }

    private function createNewInstance(): Album
    {
        return new Album();
    }
}
