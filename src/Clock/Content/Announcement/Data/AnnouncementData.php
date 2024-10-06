<?php

declare(strict_types=1);

namespace App\Clock\Content\Announcement\Data;

use App\Clock\Content\Announcement\AnnouncementType;
use App\Entity\Announcement;
use App\Entity\Audio;
use DateTimeInterface;

class AnnouncementData
{
    private string $identifier;

    private Audio $audio;

    private int $useCounter = 0;

    private ?DateTimeInterface $lastUsage = null;

    private AnnouncementType $announcementType = AnnouncementType::TO_LATE;

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): AnnouncementData
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getAudio(): Audio
    {
        return $this->audio;
    }

    public function setAudio(Audio $audio): AnnouncementData
    {
        $this->audio = $audio;
        return $this;
    }

    public function getUseCounter(): int
    {
        return $this->useCounter;
    }

    public function setUseCounter(int $useCounter): AnnouncementData
    {
        $this->useCounter = $useCounter;
        return $this;
    }

    public function getLastUsage(): ?DateTimeInterface
    {
        return $this->lastUsage;
    }

    public function setLastUsage(?DateTimeInterface $lastUsage): AnnouncementData
    {
        $this->lastUsage = $lastUsage;
        return $this;
    }

    public function getAnnouncementType(): AnnouncementType
    {
        return $this->announcementType;
    }

    public function setAnnouncementType(AnnouncementType $announcementType): AnnouncementData
    {
        $this->announcementType = $announcementType;
        return $this;
    }

    public function initFromEntity(Announcement $announcement): AnnouncementData
    {
        $this->setAudio($announcement->getAudio());
        $this->setLastUsage($announcement->getLastUsage());
        $this->setIdentifier($announcement->getIdentifier());
        $this->setUseCounter($announcement->getUseCounter());
        $this->setAnnouncementType($announcement->getType());

        return $this;
    }
}
