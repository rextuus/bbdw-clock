<?php

declare(strict_types=1);

namespace App\Discography\Content\Contribution\Data;

use App\Discography\Content\Contribution\ContributionType;
use App\Entity\God;
use App\Entity\Song;

class ContributionData
{
    private God $god;

    private Song $song;

    private ContributionType $contributionType;

    public function getGod(): God
    {
        return $this->god;
    }

    public function setGod(God $god): ContributionData
    {
        $this->god = $god;
        return $this;
    }

    public function getSong(): Song
    {
        return $this->song;
    }

    public function setSong(Song $song): ContributionData
    {
        $this->song = $song;
        return $this;
    }

    public function getContributionType(): ContributionType
    {
        return $this->contributionType;
    }

    public function setContributionType(ContributionType $contributionType): ContributionData
    {
        $this->contributionType = $contributionType;
        return $this;
    }
}
