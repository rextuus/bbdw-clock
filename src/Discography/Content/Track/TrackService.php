<?php

declare(strict_types=1);

namespace App\Discography\Content\Track;

use App\Discography\Content\Track\Data\TrackData;
use App\Entity\Track;

class TrackService
{
    public function __construct(private readonly TrackRepository $repository, private readonly TrackFactory $factory)
    {
    }

    public function createByData(TrackData $data): Track
    {
        $track = $this->factory->createByData($data);
        $this->repository->persist($track);
        return $track;
    }

    public function update(Track $track, TrackData $data, bool $flush = false): Track
    {
        $track = $this->factory->mapData($data, $track);
        $this->repository->persist($track);

        if ($flush){
            $this->repository->flush();
        }

        return $track;
    }

    /**
     * @return Track[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }
}
