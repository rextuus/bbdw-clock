<?php

declare(strict_types=1);

namespace App\Clock\Content\GameRound;

use App\Clock\Content\GameRound\Data\GameRoundData;
use App\Clock\Content\GameRound\Data\GameRoundUpdateData;
use App\Entity\GameRound;
use Exception;

class GameRoundService
{
    public function __construct(private readonly GameRoundRepository $repository, private readonly GameRoundFactory $factory)
    {
    }

    public function createByData(GameRoundData $data): GameRound
    {
        $gameRound = $this->factory->createByData($data);
        $this->repository->persist($gameRound);
        $this->repository->flush();

        return $gameRound;
    }

    public function update(GameRound $gameRound, GameRoundUpdateData $data): GameRound
    {
        $gameRound = $this->factory->mapData($data, $gameRound);
        $this->repository->persist($gameRound);
        $this->repository->flush();

        return $gameRound;
    }

    /**
     * @return GameRound[]
     */
    public function findBy(array $conditions): array
    {
        return $this->repository->findBy($conditions);
    }

    /**
     * @throws Exception
     */
    public function findCurrentRound(): GameRound
    {
        $gameRound = $this->repository->findCurrentRound();
        if (null === $gameRound) {
            throw new Exception('No active game round found');
        }

        return $gameRound;
    }
}
