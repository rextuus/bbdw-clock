<?php

namespace App\Entity;

use App\Clock\Content\Setting\SettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \App\Clock\Content\GameRound\GameRoundType;
use \App\Clock\Content\Setting\AlbumDisplayMode;
use \App\Clock\LedMatrixDisplayMode;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ledMatrixDisplayIp = null;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    #[ORM\Column(enumType: GameRoundType::class)]
    private ?GameRoundType $currentGameMode = null;

    #[ORM\Column]
    private ?int $gamesPerDayLimit = null;

    #[ORM\Column]
    private ?bool $forceNextGameInstantly = null;

    #[ORM\Column(enumType: AlbumDisplayMode::class)]
    private AlbumDisplayMode $albumDisplayMode = AlbumDisplayMode::SPLIT;

    #[ORM\Column(enumType: LedMatrixDisplayMode::class)]
    private LedMatrixDisplayMode $ledMatrixMode = LedMatrixDisplayMode::RUNNING;

    #[ORM\Column(length: 10000)]
    private string $currentLedText = 'null';

    #[ORM\Column(type: Types::JSON)]
    private array $fontColor = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLedMatrixDisplayIp(): ?string
    {
        return $this->ledMatrixDisplayIp;
    }

    public function setLedMatrixDisplayIp(string $ledMatrixDisplayIp): static
    {
        $this->ledMatrixDisplayIp = $ledMatrixDisplayIp;

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

    public function getCurrentGameMode(): ?GameRoundType
    {
        return $this->currentGameMode;
    }

    public function setCurrentGameMode(GameRoundType $currentGameMode): static
    {
        $this->currentGameMode = $currentGameMode;

        return $this;
    }

    public function getGamesPerDayLimit(): ?int
    {
        return $this->gamesPerDayLimit;
    }

    public function setGamesPerDayLimit(int $gamesPerDayLimit): static
    {
        $this->gamesPerDayLimit = $gamesPerDayLimit;

        return $this;
    }

    public function isForceNextGameInstantly(): ?bool
    {
        return $this->forceNextGameInstantly;
    }

    public function setForceNextGameInstantly(bool $forceNextGameInstantly): static
    {
        $this->forceNextGameInstantly = $forceNextGameInstantly;

        return $this;
    }

    public function getAlbumDisplayMode(): ?AlbumDisplayMode
    {
        return $this->albumDisplayMode;
    }

    public function setAlbumDisplayMode(AlbumDisplayMode $albumDisplayMode): static
    {
        $this->albumDisplayMode = $albumDisplayMode;

        return $this;
    }

    public function getLedMatrixMode(): LedMatrixDisplayMode
    {
        return $this->ledMatrixMode;
    }

    public function setLedMatrixMode(LedMatrixDisplayMode $ledMatrixMode): static
    {
        $this->ledMatrixMode = $ledMatrixMode;

        return $this;
    }

    public function getCurrentLedText(): string
    {
        return $this->currentLedText;
    }

    public function setCurrentLedText(string $currentLedText): static
    {
        $this->currentLedText = $currentLedText;

        return $this;
    }

    public function getFontColor(): array
    {
        return $this->fontColor ?? [];
    }

    public function setFontColor(?array $fontColor): static
    {
        if ($fontColor === null) {
            $fontColor = [];
        }

        $this->fontColor = $fontColor;

        return $this;
    }
}
