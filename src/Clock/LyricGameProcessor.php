<?php

declare(strict_types=1);

namespace App\Clock;

use App\Clock\Content\GameRound\Data\GameRoundData;
use App\Clock\Content\GameRound\Data\GameRoundUpdateData;
use App\Clock\Content\GameRound\GameRoundService;
use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\SettingService;
use App\Discography\Content\Album\AlbumService;
use App\Discography\Content\Contribution\ContributionType;
use App\Discography\Content\God\GodName;
use App\Discography\Content\God\GodService;
use App\Discography\Content\Lyric\LyricService;
use App\Entity\Album;
use App\Entity\Contribution;
use App\Entity\God;
use App\Entity\Track;
use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LyricGameProcessor
{
    public const BUTTON_1 = 'button1';
    public const BUTTON_2 = 'button2';
    public const BUTTON_3 = 'button3';

    public const BUTTON_TO_GOD_MAPPING = [
        self::BUTTON_1 => GodName::FELSENHEIMER,
        self::BUTTON_2 => GodName::URLAUB,
        self::BUTTON_3 => GodName::GONZALES,
    ];

    public const CORRECT_ALBUM = 'correct';
    public const TRAP_ALBUMS = 'traps';

    public function __construct(
        private readonly GameSessionService $gameSessionService,
        private readonly LyricService $lyricService,
        private readonly AlbumService $albumService,
        private readonly GameRoundService $gameRoundService,
        private readonly GodService $godService,
        private readonly LedMatrixDisplayService $ledMatrixDisplayService,
        private readonly SettingService $settingService,
    ) {
    }

    public function setNewRandomLyric(): void
    {
        $lyric = $this->lyricService->getRandomLyric();

        // store new values in db
        $this->gameSessionService->setCurrentLyric($lyric, false);
        $this->gameSessionService->setCurrentSong($lyric->getSong());

        $gameMode = $this->settingService->getCurrentGameMode();
        if ($gameMode === null) {
            $gameMode = GameRoundType::TEXTER_BOTH;
            $this->settingService->setCurrentGameMode($gameMode);
        }

        if ($gameMode === GameRoundType::ALBUM) {
            $this->gameSessionService->setCurrentAlbumChoices($this->getAlbumChoices());
        }

        $gameRoundData = new GameRoundData();
        // TODO get GameType from settings
        $gameRoundData->setType(GameRoundType::TEXTER_BOTH);
        $gameRoundData->setWon(false);
        $gameRoundData->setAttempts(0);
        $gameRoundData->setLyric($lyric);
        $this->gameRoundService->createByData($gameRoundData);

        try {
            $this->ledMatrixDisplayService->displayScrollingText($lyric->getContent());
        } catch (Exception|TransportExceptionInterface $e) {
        }
        // TODO if album cover mode is aktiv display albums there (save in game session)

        $this->gameSessionService->setForceDisplayUpdate(true);
    }

    public function evaluateButtonChoice(string $buttonName): void
    {
        $god = $this->godService->findGodByName(self::BUTTON_TO_GOD_MAPPING[$buttonName]);

        $song = $this->gameSessionService->getCurrentSong();

        $contributions = $song->getContributions()->toArray();

        $gameRoundType = $this->gameSessionService->getCurrentGameRoundType();
        $correct = false;
        match ($gameRoundType) {
            GameRoundType::TEXTER_BOTH => $correct = $this->evaluateTexterChoice(
                $god,
                $contributions,
                [ContributionType::MUSIC, ContributionType::TEXT]
            ),
            GameRoundType::TEXTER_MUSIC => $correct = $this->evaluateTexterChoice(
                $god,
                $contributions,
                [ContributionType::MUSIC]
            ),
            GameRoundType::TEXTER_LYRIC => $correct = $this->evaluateTexterChoice(
                $god,
                $contributions,
                [ContributionType::TEXT]
            ),
            GameRoundType::ALBUM => throw new Exception('To be implemented'),
        };

        if ($correct) {
            // TODO may sound or special display
        }else{
            // show looser image on display
            $this->settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::LOOSE);

            // TODO show correct answer on led-matrix
            // TODO play Tage wie solche?
        }
        // TODO what will happen next?

        // clear song
        $this->gameSessionService->freeCurrentSongAndLyric();

        // update the gameRound entity
        $gameRound = $this->gameRoundService->findCurrentRound();
        $gameRoundData = (new GameRoundUpdateData())->intiFromEntity($gameRound);

        $attempts = 1;
        if ($gameRound->getAttempts() !== null) {
            $attempts = $gameRound->getAttempts() + 1;
        }
        $gameRoundData->setAttempts($attempts);
        $gameRoundData->setFinished(new DateTime());
        $gameRoundData->setWon($correct);

        $this->gameRoundService->update($gameRound, $gameRoundData);

        // TODO check if you have more than one attempt??
        // TODO check in settings if next should automatically be displayed

        // Event evaluation finished
        $this->setNewRandomLyric();
    }

    /**
     * @param array<Contribution> $contributions
     * @param array<ContributionType> $contributionTypes
     */
    private function evaluateTexterChoice(God $god, array $contributions, array $contributionTypes): bool
    {
        foreach ($contributions as $contribution) {
            if ($contribution->getGod() === $god && in_array($contribution->getType(), $contributionTypes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, int|array<int, int>>
     * @throws Exception
     */
    public function getAlbumChoices(): array
    {
        $song = $this->gameSessionService->getCurrentSong();
        if ($song === null) {
            throw new Exception('There is no song set to current gameSession');
        }

        $tracks = $song->getTracks()->toArray();
        $randomTrack = $tracks[0];
        if (count($tracks) > 1) {
            // pick random track
            $randomTrack = $tracks[array_rand($tracks)];
        }

        $albumsToExclude = array_map(
            function (Track $track) {
                return $track->getAlbum();
            },
            $tracks
        );

        $randomAlbum = $randomTrack->getAlbum();

        if ($randomAlbum === null) {
            throw new Exception('Random track has no album');
        }

        $trapAlbums = array_map(
            function (Album $album) {
                return $album->getId();
            },
            $this->albumService->getAmountWithExclude(2, $albumsToExclude)
        );

        // better save to an entity because we have to connect the choice buttons with anything temporary
        return [
            LyricGameProcessor::CORRECT_ALBUM => $randomAlbum->getId(),
            LyricGameProcessor::TRAP_ALBUMS => $trapAlbums
        ];
    }
}
