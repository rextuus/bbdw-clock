<?php

declare(strict_types=1);

namespace App\Clock\Content\Setting;

use App\Clock\Content\Setting\Data\SettingData;
use App\Entity\Setting;

/**
 * @author Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2024 DocCheck Community GmbH
 */
class SettingFactory
{
    public function mapData(SettingData $data, Setting $setting): Setting
    {
        $setting->setCurrentGameMode($data->getCurrentGameMode());
        $setting->setForceNextGameInstantly($data->getForceNextGameInstantly());
        $setting->setGamesPerDayLimit($data->getGamesPerDayLimit());
        $setting->setLedMatrixDisplayIp($data->getLedMatrixDisplayIp());
        $setting->setAlbumDisplayMode($data->getAlbumDisplayMode());

        return $setting;
    }
}
