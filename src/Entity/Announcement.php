<?php

namespace App\Entity;

use App\Clock\Content\Announcement\AnnouncementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \App\Clock\Content\Announcement\AnnouncementType;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Audio $audio = null;

    #[ORM\Column]
    private ?int $useCounter = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUsage = null;

    #[ORM\Column(enumType: AnnouncementType::class)]
    private ?AnnouncementType $type = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAudio(): ?Audio
    {
        return $this->audio;
    }

    public function setAudio(Audio $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getUseCounter(): ?int
    {
        return $this->useCounter;
    }

    public function setUseCounter(int $useCounter): static
    {
        $this->useCounter = $useCounter;

        return $this;
    }

    public function getLastUsage(): ?\DateTimeInterface
    {
        return $this->lastUsage;
    }

    public function setLastUsage(?\DateTimeInterface $lastUsage): static
    {
        $this->lastUsage = $lastUsage;

        return $this;
    }

    public function getType(): ?AnnouncementType
    {
        return $this->type;
    }

    public function setType(AnnouncementType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
