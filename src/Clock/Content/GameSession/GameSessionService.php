<?php

declare(strict_types=1);

namespace App\Clock\Content\GameSession;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\LyricGameProcessor;
use App\Entity\Album;
use App\Entity\GameSession;
use App\Entity\Lyric;
use App\Entity\Song;

class GameSessionService
{
    private const DEFAULT_SESSION_IDENT = 'default_session';

    public function __construct(private readonly GameSessionRepository $gameSessionRepository)
    {
    }

    public function initGameSession(): GameSession
    {
        $session = $this->gameSessionRepository->findOneBy(['identifier' => self::DEFAULT_SESSION_IDENT]);
        if ($session === null) {
            $session = new GameSession();
            $session->setIdentifier(self::DEFAULT_SESSION_IDENT);
            $session->setCurrentLyric(null);
            $session->setCurrentSong(null);
            $session->setCurrentGameRoundType(GameRoundType::ALBUM);
            $session->setCurrentAlbumChoices([]);
            $session->setForceDisplayUpdate(false);

            $this->gameSessionRepository->persist($session);
            $this->gameSessionRepository->flush();
        }

        return $session;
    }

    public function getGameSession(): GameSession
    {
        $session = $this->gameSessionRepository->findOneBy(['identifier' => self::DEFAULT_SESSION_IDENT]);
        if ($session === null) {
            $session = $this->initGameSession();
        }

        return $session;
    }

    public function setCurrentLyric(Lyric $lyric, bool $flush = true): void
    {
        $session = $this->getGameSession();
        $session->setCurrentLyric($lyric);

        $this->saveSession($session, $flush);
    }

    public function setCurrentSong(Song $song, bool $flush = true): void
    {
        $session = $this->getGameSession();
        $session->setCurrentSong($song);

        $this->saveSession($session, $flush);
    }

    public function getCurrentSong(): ?Song
    {
        return $this->getGameSession()->getCurrentSong();
    }

    public function getCurrentGameRoundType(): GameRoundType
    {
        return $this->getGameSession()->getCurrentGameRoundType();
    }

    private function saveSession(GameSession $session, bool $flush = true): void
    {
        $this->gameSessionRepository->persist($session);

        if ($flush){
            $this->gameSessionRepository->flush();
        }
    }

    public function freeCurrentSongAndLyric(): void
    {
        $session = $this->getGameSession();
        $session->setCurrentSong(null);
        $session->setCurrentLyric(null);

        $this->saveSession($session);
    }

    /**
     * @param array<string, int|array<int, int>> $albumChoices
     */
    public function setCurrentAlbumChoices(array $albumChoices): void
    {
        $session = $this->getGameSession();
        $session->setCurrentAlbumChoices($albumChoices);

        $this->saveSession($session);
    }

    public function getCurrenAlbumChoices(): array
    {
        return $this->getGameSession()->getCurrentAlbumChoices();
    }

    public function setForceDisplayUpdate(bool $forceDisplayUpdate): void
    {
        $session = $this->getGameSession();
        $session->setForceDisplayUpdate($forceDisplayUpdate);

        $this->saveSession($session);
    }

    public function isForceDisplayUpdate(): bool
    {
        return $this->getGameSession()->isForceDisplayUpdate();
    }
}
