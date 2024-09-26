<?php

declare(strict_types=1);

namespace App\Discography\Content\Contribution;

use App\Discography\Content\Contribution\Data\ContributionData;
use App\Entity\Contribution;

class ContributionService
{
    public function __construct(
        private readonly ContributionRepository $repository,
        private readonly ContributionFactory $factory
    ) {
    }

    public function createByData(ContributionData $data): Contribution
    {
        $contribution = $this->factory->createByData($data);
        $this->repository->persist($contribution);
        return $contribution;
    }

    public function update(Contribution $contribution, ContributionData $data): Contribution
    {
        $contribution = $this->factory->mapData($data, $contribution);
        $this->repository->persist($contribution);
        return $contribution;
    }

    /**
     * @return Contribution[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }
}
