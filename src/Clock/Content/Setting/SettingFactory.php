<?php

declare(strict_types=1);

namespace App\Clock\Content\Setting;

use App\Clock\Content\Setting\Data\SettingData;
use App\Entity\Setting;

class SettingFactory
{
    public function mapData(SettingData $data, Setting $setting): Setting
    {
        $setting->setCurrentGameMode($data->getCurrentGameMode());
        $setting->setForceNextGameInstantly($data->getForceNextGameInstantly());
        $setting->setGamesPerDayLimit($data->getGamesPerDayLimit());
        $setting->setLedMatrixDisplayIp($data->getLedMatrixDisplayIp());
        $setting->setAlbumDisplayMode($data->getAlbumDisplayMode());
        $setting->setLedMatrixMode($data->getLedMatrixDisplayMode());
        $setting->setFontColor($data->getFontColor());

        return $setting;
    }
}
