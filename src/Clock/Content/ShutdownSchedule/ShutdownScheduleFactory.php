<?php

declare(strict_types=1);

namespace App\Clock\Content\ShutdownSchedule;

use App\Clock\Content\ShutdownSchedule\Data\ShutdownScheduleData;
use App\Entity\ShutdownSchedule;

class ShutdownScheduleFactory
{
    public function createByData(ShutdownScheduleData $data): ShutdownSchedule
    {
        $shutdownSchedule = $this->createNewInstance();
        $this->mapData($data, $shutdownSchedule);
        return $shutdownSchedule;
    }

    public function mapData(ShutdownScheduleData $data,  ShutdownSchedule $shutdownSchedule): ShutdownSchedule
    {
        $shutdownSchedule->setShutdownTime($data->getShutdownTime());
        $shutdownSchedule->setRestartTime($data->getRestartTime());

        return $shutdownSchedule;
    }

    private function createNewInstance(): ShutdownSchedule
    {
        return new ShutdownSchedule();
    }
}
