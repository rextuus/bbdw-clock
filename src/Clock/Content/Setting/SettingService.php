<?php

declare(strict_types=1);

namespace App\Clock\Content\Setting;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\Data\SettingData;
use App\Entity\Setting;

class SettingService
{
    private const DEFAULT_SETTING_IDENT = 'default_session';

    public function __construct(
        private readonly SettingRepository $settingRepository,
        private readonly SettingFactory $settingFactory,
        private readonly GameSessionService $gameSessionService
    ) {
    }

    public function initSettings(): Setting
    {
        $session = $this->settingRepository->findOneBy(['identifier' => self::DEFAULT_SETTING_IDENT]);
        if ($session === null) {
            $session = new Setting();
            $session->setIdentifier(self::DEFAULT_SETTING_IDENT);
            $session->setLedMatrixDisplayIp('192.168.178.1');
            $session->setCurrentGameMode(GameRoundType::ALBUM);
            $session->setGamesPerDayLimit(3);
            $session->setForceNextGameInstantly(true);
            $session->setAlbumDisplayMode(AlbumDisplayMode::SPLIT);

            $this->settingRepository->persist($session);
            $this->settingRepository->flush();
        }

        return $session;
    }

    public function getSettings(): Setting
    {
        $session = $this->settingRepository->findOneBy(['identifier' => self::DEFAULT_SETTING_IDENT]);
        if ($session === null) {
            $session = $this->initSettings();
        }

        return $session;
    }

    public function getLedMatrixDisplayIp(): string
    {
        return $this->getSettings()->getLedMatrixDisplayIp();
    }

    public function updateSettings(Setting $setting, SettingData $settingData): Setting
    {
        $this->settingFactory->mapData($settingData, $setting);
        $this->settingRepository->persist($setting);
        $this->settingRepository->flush();

        return $setting;
    }

    public function getCurrentGameMode(): ?GameRoundType
    {
        return $this->getSettings()->getCurrentGameMode();
    }

    public function setCurrentGameMode(GameRoundType $gameMode): void
    {
        $settings = $this->getSettings();
        $settings->setCurrentGameMode($gameMode);

        $this->settingRepository->persist($settings);
        $this->settingRepository->flush();
    }

    public function getCurrentAlbumDisplayMode(): AlbumDisplayMode
    {
        return $this->getSettings()->getAlbumDisplayMode();
    }

    public function setCurrentAlbumDisplayMode(AlbumDisplayMode $albumDisplayMode): void
    {
        $settings = $this->getSettings();
        $settings->setAlbumDisplayMode($albumDisplayMode);

        $this->settingRepository->persist($settings);
        $this->settingRepository->flush();

        $this->gameSessionService->setForceDisplayUpdate(true);
    }
}
