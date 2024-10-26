<?php

namespace App\Entity;

use App\Clock\Content\ShutdownSchedule\ScheduleListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleListRepository::class)]
class ScheduleList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, ShutdownSchedule>
     */
    #[ORM\OneToMany(targetEntity: ShutdownSchedule::class, mappedBy: 'scheduleList', cascade: ['persist', 'remove'])]
    private Collection $schedules;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ShutdownSchedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(ShutdownSchedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setScheduleList($this);
        }

        return $this;
    }

    public function removeSchedule(ShutdownSchedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getScheduleList() === $this) {
                $schedule->setScheduleList(null);
            }
        }

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }
}
