<?php

declare(strict_types=1);

namespace App\Clock\Content\Announcement;

use App\Clock\Content\Announcement\Data\AnnouncementData;
use App\Entity\Announcement;

class AnnouncementService
{
    public function __construct(private readonly AnnouncementRepository $repository, private readonly AnnouncementFactory $factory)
    {
    }

    public function createByData(AnnouncementData $data): Announcement
    {
        $announcement = $this->factory->createByData($data);
        $this->repository->persist($announcement);
        $this->repository->flush();

        return $announcement;
    }

    public function update(Announcement $announcement, AnnouncementData $data): Announcement
    {
        $announcement = $this->factory->mapData($data, $announcement);
        $this->repository->persist($announcement);
        $this->repository->flush();

        return $announcement;
    }

    /**
     * @return Announcement[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }
}
