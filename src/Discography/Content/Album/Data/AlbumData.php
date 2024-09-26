<?php

declare(strict_types=1);

namespace App\Discography\Content\Album\Data;

use App\Discography\Content\Album\ReleaseType;
use App\Discography\Import\Parser\AlbumMetaData;
use App\Entity\Album;
use App\Entity\File;

class AlbumData
{
    private string $name;

    private int $year;

    private ?int $amountOfTracks = null;

    private ReleaseType $releaseType;

    /**
     * @var array<int, string>|null
     */
    private ?array $rawTrackData = null;

    private ?string $coverImageUrl = null;
    private string $detailUrl;

    private ?File $cover = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AlbumData
    {
        $this->name = $name;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): AlbumData
    {
        $this->year = $year;
        return $this;
    }

    public function getAmountOfTracks(): ?int
    {
        return $this->amountOfTracks;
    }

    public function setAmountOfTracks(?int $amountOfTracks): AlbumData
    {
        $this->amountOfTracks = $amountOfTracks;
        return $this;
    }

    public function getReleaseType(): ReleaseType
    {
        return $this->releaseType;
    }

    public function setReleaseType(ReleaseType $releaseType): AlbumData
    {
        $this->releaseType = $releaseType;
        return $this;
    }

    public function getRawTrackData(): ?array
    {
        return $this->rawTrackData;
    }

    public function setRawTrackData(?array $rawTrackData): AlbumData
    {
        $this->rawTrackData = $rawTrackData;
        return $this;
    }

    public function getCoverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function setCoverImageUrl(?string $coverImageUrl): AlbumData
    {
        $this->coverImageUrl = $coverImageUrl;
        return $this;
    }

    public function getDetailUrl(): string
    {
        return $this->detailUrl;
    }

    public function setDetailUrl(string $detailUrl): AlbumData
    {
        $this->detailUrl = $detailUrl;
        return $this;
    }

    public function getCover(): ?File
    {
        return $this->cover;
    }

    public function setCover(?File $cover): AlbumData
    {
        $this->cover = $cover;
        return $this;
    }

    public static function fromMetaData(AlbumMetaData $data): AlbumData
    {
        $albumData = new self();
        $albumData->setYear($data->getReleaseYear());
        $albumData->setName($data->getName());
        $albumData->setReleaseType($data->getType());
        $albumData->setDetailUrl($data->getDetailUrl());
        $albumData->setAmountOfTracks(null);
        $albumData->setRawTrackData(null);
        $albumData->setCoverImageUrl(null);

        return $albumData;
    }

    public static function initFromEntity(Album $album): AlbumData
    {
        $albumData = new self();
        $albumData->setYear($album->getYear());
        $albumData->setName($album->getName());
        $albumData->setReleaseType($album->getType());
        $albumData->setAmountOfTracks($album->getAmountOfTracks());
        $albumData->setRawTrackData($album->getRawTrackData());
        $albumData->setCoverImageUrl($album->getCoverImageUrl());
        $albumData->setDetailUrl($album->getDetailUrl());
        $albumData->setCover($album->getFile());

        return $albumData;
    }
}
