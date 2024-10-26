<?php

declare(strict_types=1);

namespace App\Clock\Content\ShutdownSchedule;

use App\Clock\Content\ShutdownSchedule\Data\ShutdownScheduleData;
use App\Entity\ScheduleList;
use App\Entity\ShutdownSchedule;

class ShutdownScheduleService
{
    private const DEFAULT_SCHEDULE_LIST = 'default_schedule_list';

    public function __construct(
        private readonly ShutdownScheduleRepository $repository,
        private readonly ScheduleListRepository $scheduleListRepository,
        private readonly ShutdownScheduleFactory $factory
    ) {
    }

    public function createByData(ShutdownScheduleData $data): ShutdownSchedule
    {
        $shutdownSchedule = $this->factory->createByData($data);
        $shutdownSchedule->setScheduleList($this->getScheduleList());

        $this->repository->persist($shutdownSchedule);
        $this->repository->flush();

        return $shutdownSchedule;
    }

    public function update(ShutdownSchedule $shutdownSchedule, ShutdownScheduleData $data): ShutdownSchedule
    {
        $shutdownSchedule = $this->factory->mapData($data, $shutdownSchedule);
        $shutdownSchedule->setScheduleList($this->getScheduleList());

        $this->repository->persist($shutdownSchedule);
        $this->repository->flush();

        return $shutdownSchedule;
    }

    /**
     * @return ShutdownSchedule[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }

    public function getScheduleList(): ScheduleList
    {
        $list = $this->scheduleListRepository->findOneBy(['identifier' => self::DEFAULT_SCHEDULE_LIST]);
        if ($list === null) {
            $list = new ScheduleList();
            $list->setIdentifier(self::DEFAULT_SCHEDULE_LIST);
            $this->scheduleListRepository->persist($list);
            $this->scheduleListRepository->flush();
        }

        return $list;
    }
}
