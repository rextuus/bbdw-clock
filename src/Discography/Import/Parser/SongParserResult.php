<?php

declare(strict_types=1);

namespace App\Discography\Import\Parser;

class SongParserResult
{
    private string $title;

    private SongMetaData $metaData;

    private string $detailUrl;

    /**
     * @var array<AlbumMetaData>
     */
    private array $albumMetaData;

    /**
     * @var array<string>
     */
    private array $lyrics;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): SongParserResult
    {
        $this->title = $title;
        return $this;
    }

    public function getDetailUrl(): string
    {
        return $this->detailUrl;
    }

    public function setDetailUrl(string $detailUrl): SongParserResult
    {
        $this->detailUrl = $detailUrl;
        return $this;
    }

    public function getAlbumMetaData(): array
    {
        return $this->albumMetaData;
    }

    public function setAlbumMetaData(array $albumMetaData): SongParserResult
    {
        $this->albumMetaData = $albumMetaData;
        return $this;
    }

    public function getLyrics(): array
    {
        return $this->lyrics;
    }

    public function setLyrics(array $lyrics): SongParserResult
    {
        $this->lyrics = $lyrics;
        return $this;
    }

    public function getMetaData(): SongMetaData
    {
        return $this->metaData;
    }

    public function setMetaData(SongMetaData $metaData): SongParserResult
    {
        $this->metaData = $metaData;
        return $this;
    }
}
