<?php

declare(strict_types=1);

namespace App\Discography\Content\Song;

use App\Discography\Content\Song\Data\SongData;
use App\Entity\Song;

class SongService
{
    public function __construct(private readonly SongRepository $repository, private readonly SongFactory $factory)
    {
    }

    public function createByData(SongData $data): Song
    {
        $song = $this->factory->createByData($data);
        $this->repository->persist($song);
        return $song;
    }

    public function update(Song $song, SongData $data): Song
    {
        $song = $this->factory->mapData($data, $song);
        $this->repository->persist($song);
        return $song;
    }

    /**
     * @return Song[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }

    /**
     * @return array<Song>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
