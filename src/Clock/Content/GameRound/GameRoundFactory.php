<?php

declare(strict_types=1);

namespace App\Clock\Content\GameRound;

use App\Clock\Content\GameRound\Data\GameRoundData;
use App\Clock\Content\GameRound\Data\GameRoundUpdateData;
use App\Entity\GameRound;
use DateTime;

class GameRoundFactory
{
    public function createByData(GameRoundData $data): GameRound
    {
        $gameRound = $this->createNewInstance();
        $this->mapData($data, $gameRound);
        return $gameRound;
    }

    public function mapData(GameRoundData $data, GameRound $gameRound): GameRound
    {
        $gameRound->setType($data->getType());
        $gameRound->setWon($data->isWon());
        $gameRound->setAttempts($data->getAttempts());
        $gameRound->setLyric($data->getLyric());
        $gameRound->setFinished($data->getFinished());

        if (!$data instanceof GameRoundUpdateData){
            $gameRound->setStarted(new DateTime());
        }

        return $gameRound;
    }

    private function createNewInstance(): GameRound
    {
        return new GameRound();
    }
}
