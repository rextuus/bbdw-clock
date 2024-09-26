<?php

declare(strict_types=1);

namespace App\Discography\Content\Contribution;

use App\Discography\Content\Contribution\Data\ContributionData;
use App\Entity\Contribution;

class ContributionFactory
{
    public function createByData(ContributionData $data): Contribution
    {
        $contribution = $this->createNewInstance();
        $this->mapData($data, $contribution);
        return $contribution;
    }

    public function mapData(ContributionData $data, Contribution $contribution): Contribution
    {
        $contribution->setType($data->getContributionType());
        $contribution->setGod($data->getGod());
        $contribution->setSong($data->getSong());

        return $contribution;
    }

    private function createNewInstance(): Contribution
    {
        return new Contribution();
    }
}
