<?php

declare(strict_types=1);

namespace App\Clock\Content\GameRound\Data;

use App\Clock\Content\GameRound\GameRoundType;
use App\Entity\GameRound;
use App\Entity\Lyric;
use DateTime;

class GameRoundData
{
    private ?DateTime $finished = null;

    private GameRoundType $type;

    private bool $won;

    private int $attempts;

    private Lyric $lyric;

    public function getFinished(): ?DateTime
    {
        return $this->finished;
    }

    public function setFinished(?DateTime $finished): GameRoundData
    {
        $this->finished = $finished;
        return $this;
    }

    public function getType(): GameRoundType
    {
        return $this->type;
    }

    public function setType(GameRoundType $type): GameRoundData
    {
        $this->type = $type;
        return $this;
    }

    public function isWon(): bool
    {
        return $this->won;
    }

    public function setWon(bool $won): GameRoundData
    {
        $this->won = $won;
        return $this;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): GameRoundData
    {
        $this->attempts = $attempts;
        return $this;
    }

    public function getLyric(): Lyric
    {
        return $this->lyric;
    }

    public function setLyric(Lyric $lyric): GameRoundData
    {
        $this->lyric = $lyric;
        return $this;
    }

    public function intiFromEntity(GameRound $round): GameRoundData
    {
        $this->setAttempts($round->getAttempts());
        $this->setFinished($round->getFinished());
        $this->setType($round->getType());
        $this->setWon($round->isWon());
        $this->setLyric($round->getLyric());

        return $this;
    }
}
