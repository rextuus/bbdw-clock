<?php

declare(strict_types=1);

namespace App\Clock\Content\Setting\Data;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\Setting\AlbumDisplayMode;

class SettingData
{
    private ?string $ledMatrixDisplayIp;
    private ?GameRoundType $currentGameMode;
    private ?int $gamesPerDayLimit;
    private ?bool $forceNextGameInstantly;

    private AlbumDisplayMode $albumDisplayMode;

    public function getLedMatrixDisplayIp(): ?string
    {
        return $this->ledMatrixDisplayIp;
    }

    public function setLedMatrixDisplayIp(?string $ledMatrixDisplayIp): SettingData
    {
        $this->ledMatrixDisplayIp = $ledMatrixDisplayIp;
        return $this;
    }

    public function getCurrentGameMode(): ?GameRoundType
    {
        return $this->currentGameMode;
    }

    public function setCurrentGameMode(?GameRoundType $currentGameMode): SettingData
    {
        $this->currentGameMode = $currentGameMode;
        return $this;
    }

    public function getGamesPerDayLimit(): ?int
    {
        return $this->gamesPerDayLimit;
    }

    public function setGamesPerDayLimit(?int $gamesPerDayLimit): SettingData
    {
        $this->gamesPerDayLimit = $gamesPerDayLimit;
        return $this;
    }

    public function getForceNextGameInstantly(): ?bool
    {
        return $this->forceNextGameInstantly;
    }

    public function setForceNextGameInstantly(?bool $forceNextGameInstantly): SettingData
    {
        $this->forceNextGameInstantly = $forceNextGameInstantly;
        return $this;
    }

    public function getAlbumDisplayMode(): AlbumDisplayMode
    {
        return $this->albumDisplayMode;
    }

    public function setAlbumDisplayMode(AlbumDisplayMode $albumDisplayMode): SettingData
    {
        $this->albumDisplayMode = $albumDisplayMode;
        return $this;
    }
}
