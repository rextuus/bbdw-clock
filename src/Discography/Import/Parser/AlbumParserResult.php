<?php

declare(strict_types=1);

namespace App\Discography\Import\Parser;

class AlbumParserResult
{
    private string $imageLink;

    /**
     * @var array<int, string>
     */
    private array $trackList;

    public function getImageLink(): string
    {
        return $this->imageLink;
    }

    public function setImageLink(string $imageLink): AlbumParserResult
    {
        $this->imageLink = $imageLink;
        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getTrackList(): array
    {
        return $this->trackList;
    }

    /**
     * @param array<int, string> $trackList
     */
    public function setTrackList(array $trackList): AlbumParserResult
    {
        $this->trackList = $trackList;
        return $this;
    }
}
