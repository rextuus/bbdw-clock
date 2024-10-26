<?php

declare(strict_types=1);

namespace App\Clock\Content\ShutdownSchedule\Data;

use DateTimeInterface;

class ShutdownScheduleData
{
    private ?DateTimeInterface $shutdownTime = null;
    private ?DateTimeInterface $restartTime = null;

    public function getShutdownTime(): ?DateTimeInterface
    {
        return $this->shutdownTime;
    }

    public function setShutdownTime(?DateTimeInterface $shutdownTime): ShutdownScheduleData
    {
        $this->shutdownTime = $shutdownTime;
        return $this;
    }

    public function getRestartTime(): ?DateTimeInterface
    {
        return $this->restartTime;
    }

    public function setRestartTime(?DateTimeInterface $restartTime): ShutdownScheduleData
    {
        $this->restartTime = $restartTime;
        return $this;
    }
}
