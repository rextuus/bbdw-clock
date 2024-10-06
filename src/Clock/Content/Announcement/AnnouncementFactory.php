<?php

declare(strict_types=1);

namespace App\Clock\Content\Announcement;

use App\Clock\Content\Announcement\Data\AnnouncementData;
use App\Entity\Announcement;

class AnnouncementFactory
{
    public function createByData(AnnouncementData $data): Announcement
    {
        $announcement = $this->createNewInstance();
        $this->mapData($data, $announcement);
        return $announcement;
    }
    
    public function mapData(AnnouncementData $data, Announcement $announcement): Announcement
    {
        $announcement->setIdentifier($data->getIdentifier());
        $announcement->setAudio($data->getAudio());
        $announcement->setUseCounter($data->getUseCounter());
        $announcement->setLastUsage($data->getLastUsage());
        $announcement->setType($data->getAnnouncementType());

        return $announcement;
    }
    
    private function createNewInstance(): Announcement
    {
        return new Announcement();
    }
}
