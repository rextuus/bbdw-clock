<?php

declare(strict_types=1);

namespace App\Discography\Import\Parser;

use App\Discography\Content\Album\ReleaseType;

class AlbumMetaData
{
    private string $name;

    private ReleaseType $type;

    private int $releaseYear;

    private string $detailUrl;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AlbumMetaData
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ReleaseType
    {
        return $this->type;
    }

    public function setType(ReleaseType $type): AlbumMetaData
    {
        $this->type = $type;
        return $this;
    }

    public function getReleaseYear(): int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(int $releaseYear): AlbumMetaData
    {
        $this->releaseYear = $releaseYear;
        return $this;
    }

    public function getDetailUrl(): string
    {
        return $this->detailUrl;
    }

    public function setDetailUrl(string $detailUrl): AlbumMetaData
    {
        $this->detailUrl = $detailUrl;
        return $this;
    }
}
