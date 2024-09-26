<?php

declare(strict_types=1);

namespace App\Discography\Content\Album;

use App\Discography\Content\Album\Data\AlbumData;
use App\Entity\Album;

class AlbumService
{
    public function __construct(private readonly AlbumRepository $repository, private readonly AlbumFactory $factory)
    {
    }

    public function createByData(AlbumData $data): Album
    {
        $album = $this->factory->createByData($data);
        $this->repository->persist($album);

        return $album;
    }

    public function update(Album $album, AlbumData $data, bool $flush = false): Album
    {
        $album = $this->factory->mapData($data, $album);
        $this->repository->persist($album);

        if ($flush) {
            $this->repository->flush();
        }

        return $album;
    }

    /**
     * @return Album[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }

    public function findById(int $id): Album
    {
        return $this->repository->find($id);
    }

    public function findByNameAndType(string $name, ReleaseType $type): ?Album
    {
        return $this->repository->findOneBy(['name' => $name, 'type' => $type]);
    }

    /**
     * @return array<Album>
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array<Album> $excludes
     * @return array<Album>
     */
    public function getAmountWithExclude(int $amount, array $excludes): array
    {
        $candidates = $this->repository->getAmountWithExclude($excludes);

        // pick random amount from candidates
        shuffle($candidates);

        return array_slice($candidates, 0, $amount);
    }
}
