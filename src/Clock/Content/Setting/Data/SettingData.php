<?php

declare(strict_types=1);

namespace App\Clock\Content\Setting\Data;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\LedMatrixDisplayMode;
use App\Entity\Setting;

class SettingData
{
    private ?string $ledMatrixDisplayIp;
    private ?GameRoundType $currentGameMode;
    private ?int $gamesPerDayLimit;
    private ?bool $forceNextGameInstantly;

    private AlbumDisplayMode $albumDisplayMode;
    private LedMatrixDisplayMode $ledMatrixDisplayMode;

    private array $fontColor = ['r' => 0, 'g' => 0, 'b' => 0];


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

    public function getLedMatrixDisplayMode(): LedMatrixDisplayMode
    {
        return $this->ledMatrixDisplayMode;
    }

    public function setLedMatrixDisplayMode(LedMatrixDisplayMode $ledMatrixDisplayMode): SettingData
    {
        $this->ledMatrixDisplayMode = $ledMatrixDisplayMode;
        return $this;
    }

    public function getFontColor(): array
    {
        return $this->fontColor;
    }

    public function setFontColor(array $fontColor): SettingData
    {
        $this->fontColor = $fontColor;
        return $this;
    }

    public function initFromEntity(Setting $setting): self
    {
        $this->setAlbumDisplayMode($setting->getAlbumDisplayMode());
        $this->setLedMatrixDisplayIp($setting->getLedMatrixDisplayIp());
        $this->setCurrentGameMode($setting->getCurrentGameMode());
        $this->setGamesPerDayLimit($setting->getGamesPerDayLimit());
        $this->setForceNextGameInstantly($setting->isForceNextGameInstantly());
        $this->setLedMatrixDisplayMode($setting->getLedMatrixMode());
        $this->setFontColor($setting->getFontColor());

        return $this;
    }
}
