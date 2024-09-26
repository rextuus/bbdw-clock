<?php

namespace App\Message;

use App\Discography\Import\Parser\AlbumMetaData;

final class ParseAlbumMessage
{
    private AlbumMetaData $metaData;

    public function __construct(AlbumMetaData $metaData)
    {
        $this->metaData = $metaData;
    }

    public function getMetaData(): AlbumMetaData
    {
        return $this->metaData;
    }

    public function setMetaData(AlbumMetaData $metaData): ParseAlbumMessage
    {
        $this->metaData = $metaData;
        return $this;
    }
}
