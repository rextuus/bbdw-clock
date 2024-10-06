<?php

namespace App\Entity;

use App\File\FileRelationTrait;
use App\Repository\AudioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AudioRepository::class)]
class Audio
{
    use FileRelationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    #[ORM\Column]
    private ?float $playTime = null;

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

    public function getPlayTime(): ?float
    {
        return $this->playTime;
    }

    public function setPlayTime(float $playTime): static
    {
        $this->playTime = $playTime;

        return $this;
    }
}
