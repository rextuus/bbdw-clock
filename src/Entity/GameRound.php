<?php

namespace App\Entity;

use App\Clock\Content\GameRound\GameRoundRepository;
use App\Clock\Content\GameRound\GameRoundType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRoundRepository::class)]
class GameRound
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $started = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finished = null;

    #[ORM\Column(enumType: GameRoundType::class)]
    private ?GameRoundType $type = null;

    #[ORM\Column]
    private ?bool $won = null;

    #[ORM\Column]
    private ?int $attempts = null;

    #[ORM\ManyToOne(inversedBy: 'gameRounds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lyric $lyric = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStarted(): ?\DateTimeInterface
    {
        return $this->started;
    }

    public function setStarted(\DateTimeInterface $started): static
    {
        $this->started = $started;

        return $this;
    }

    public function getFinished(): ?\DateTimeInterface
    {
        return $this->finished;
    }

    public function setFinished(?\DateTimeInterface $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getType(): ?GameRoundType
    {
        return $this->type;
    }

    public function setType(GameRoundType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isWon(): ?bool
    {
        return $this->won;
    }

    public function setWon(bool $won): static
    {
        $this->won = $won;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): static
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getLyric(): ?Lyric
    {
        return $this->lyric;
    }

    public function setLyric(?Lyric $lyric): static
    {
        $this->lyric = $lyric;

        return $this;
    }
}
