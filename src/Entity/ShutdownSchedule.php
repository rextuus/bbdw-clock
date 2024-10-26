<?php

namespace App\Entity;

use App\Clock\Content\ShutdownSchedule\ShutdownScheduleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShutdownScheduleRepository::class)]
class ShutdownSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?DateTimeInterface $shutdownTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?DateTimeInterface $restartTime = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ScheduleList $scheduleList = null;

    public function __construct()
    {
        $this->shutdownTime = new DateTime();
        $this->restartTime = new DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShutdownTime(): ?DateTimeInterface
    {
        return $this->shutdownTime;
    }

    public function setShutdownTime(?DateTimeInterface $shutdownTime): static
    {
        $this->shutdownTime = $shutdownTime;

        return $this;
    }

    public function getRestartTime(): ?DateTimeInterface
    {
        return $this->restartTime;
    }

    public function setRestartTime(?DateTimeInterface $restartTime): static
    {
        $this->restartTime = $restartTime;

        return $this;
    }

    public function getScheduleList(): ?ScheduleList
    {
        return $this->scheduleList;
    }

    public function setScheduleList(?ScheduleList $scheduleList): static
    {
        $this->scheduleList = $scheduleList;

        return $this;
    }
}
