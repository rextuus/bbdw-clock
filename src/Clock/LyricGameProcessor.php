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
use App\Entity\Audio;
use App\Entity\Contribution;
use App\Entity\God;
use App\Entity\Track;
use App\Message\NextGameRoundMessage;
use DateTime;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LyricGameProcessor
{
    public const BUTTON_1 = 'button_1';
    public const BUTTON_2 = 'button_2';
    public const BUTTON_3 = 'button_3';

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
        private readonly AudioService $audioService,
        private readonly MessageBusInterface $messageBus,
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
            match ($this->settingService->getLedMatrixMode()) {
                LedMatrixDisplayMode::OFF => throw new \Exception('To be implemented'),
                LedMatrixDisplayMode::PERMANENT => $this->ledMatrixDisplayService->displayStaticText($lyric->getContent()),
                LedMatrixDisplayMode::RUNNING => $this->ledMatrixDisplayService->displayScrollingText($lyric->getContent()),
            };
        } catch (Exception|TransportExceptionInterface $e) {
            dd($e);
        }
        // TODO if album cover mode is aktiv display albums there (save in game session)

        $this->gameSessionService->setForceDisplayUpdate(true);
        $this->settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::CAROUSEL);
    }

    public function evaluateButtonChoice(string $buttonName): ?Audio
    {
        $god = $this->godService->findGodByName(self::BUTTON_TO_GOD_MAPPING[$buttonName]);
        $buttonNr = (int)explode('_', $buttonName)[1];

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
            GameRoundType::ALBUM => $correct = $this->evaluateAlbumChoice($buttonNr)
        };

        $audio = null;
        if ($correct) {
            // TODO may sound or special display
            $audio = $this->audioService->getRandomWinImprovisation();
            $this->settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::WIN);

        }else{
            // show looser image on display
            $this->settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::LOOSE);
            $audio = $this->audioService->getAudioByIdentifier(AudioService::LOOSE_AUDIO);

            // TODO show correct answer on led-matrix
            // TODO play Tage wie solche?
        }
        // TODO what will happen next?

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

        // place event which is handled after the audio is finished
        $playtime = $audio->getPlayTime();
        $delay = ((int)($playtime) + 3) * 1000;
        $envelope = (new Envelope(new NextGameRoundMessage()))->with(new DelayStamp($delay));
        $this->messageBus->dispatch($envelope);

        return $audio;
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

        $buttonNumbers = range(1, 3);
        shuffle($buttonNumbers);

        $index = -1;
        $trapAlbums = array_map(
            function (Album $album) use (&$index, $buttonNumbers) {
                $index++;
                return [
                    'id' => $album->getId(),
                    'buttonNr' => $buttonNumbers[$index],
                ];
            },
            $this->albumService->getAmountWithExclude(2, $albumsToExclude)
        );

        // better save to an entity because we have to connect the choice buttons with anything temporary
        return [
            LyricGameProcessor::CORRECT_ALBUM => [['id' => $randomAlbum->getId(), 'buttonNr' => $buttonNumbers[2]]],
            LyricGameProcessor::TRAP_ALBUMS => $trapAlbums
        ];
    }

    private function evaluateAlbumChoice(int $buttonNr): bool
    {
        $albumChoices = $this->gameSessionService->getCurrenAlbumChoices();
        $correct = $albumChoices[self::CORRECT_ALBUM][0]['buttonNr'];

        return $correct === $buttonNr;
    }
}
