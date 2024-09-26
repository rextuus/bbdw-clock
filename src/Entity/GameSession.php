<?php

namespace App\Entity;

use App\Clock\Content\GameSession\GameSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \App\Clock\Content\GameRound\GameRoundType;

#[ORM\Entity(repositoryClass: GameSessionRepository::class)]
class GameSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Song $currentSong = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Lyric $currentLyric = null;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    #[ORM\Column(enumType: GameRoundType::class)]
    private ?GameRoundType $currentGameRoundType = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $currentAlbumChoices = [];

    #[ORM\Column]
    private bool $forceDisplayUpdate = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentSong(): ?Song
    {
        return $this->currentSong;
    }

    public function setCurrentSong(?Song $currentSong): static
    {
        $this->currentSong = $currentSong;

        return $this;
    }

    public function getCurrentLyric(): ?Lyric
    {
        return $this->currentLyric;
    }

    public function setCurrentLyric(?Lyric $currentLyric): static
    {
        $this->currentLyric = $currentLyric;

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

    public function getCurrentGameRoundType(): ?GameRoundType
    {
        return $this->currentGameRoundType;
    }

    public function setCurrentGameRoundType(GameRoundType $currentGameRoundType): static
    {
        $this->currentGameRoundType = $currentGameRoundType;

        return $this;
    }

    public function getCurrentAlbumChoices(): array
    {
        return $this->currentAlbumChoices;
    }

    public function setCurrentAlbumChoices(array $currentAlbumChoices): static
    {
        $this->currentAlbumChoices = $currentAlbumChoices;

        return $this;
    }

    public function isForceDisplayUpdate(): bool
    {
        return $this->forceDisplayUpdate;
    }

    public function setForceDisplayUpdate(bool $forceDisplayUpdate): static
    {
        $this->forceDisplayUpdate = $forceDisplayUpdate;

        return $this;
    }
}
