<?php

declare(strict_types=1);

namespace App\Discography\Import;

use App\Discography\Content\Album\AlbumService;
use App\Discography\Content\Album\Data\AlbumData;
use App\Discography\Content\Contribution\ContributionService;
use App\Discography\Content\Contribution\ContributionType;
use App\Discography\Content\Contribution\Data\ContributionData;
use App\Discography\Content\God\GodName;
use App\Discography\Content\God\GodService;
use App\Discography\Content\Lyric\LyricService;
use App\Discography\Content\Song\Data\SongData;
use App\Discography\Content\Song\SongService;
use App\Discography\Content\Track\Data\TrackData;
use App\Discography\Content\Track\TrackService;
use App\Discography\Import\Parser\AlbumMetaData;
use App\Discography\Import\Parser\Parser;
use App\Entity\Song;
use App\Message\ParseAlbumMessage;
use App\Message\ParseSongMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportService
{
    public function __construct(
        private readonly Parser $parser,
        private readonly GodService $godService,
        private readonly ContributionService $contributionService,
        private readonly SongService $songService,
        private readonly LyricService $lyricService,
        private readonly AlbumService $albumService,
        private readonly TrackService $trackService,
        private readonly MessageBusInterface $messageBus,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function importSongList(): void
    {
        $detailLinks = $this->parser->parseSongList();

        foreach ($detailLinks as $detailLink) {
            $message = new ParseSongMessage($detailLink);
            $this->messageBus->dispatch($message);
        }
    }

    public function importSong(string $url): void
    {
        $songParserResult = $this->parser->parseSongInformation($url);

        // store song
        $songData = new SongData();
        $songData->setName($songParserResult->getTitle());
        $song = $this->songService->createByData($songData);

        // store text contributions
        $this->storeContribution($songParserResult->getMetaData()->getText(), ContributionType::TEXT, $song);

        // store music contributions
        $this->storeContribution($songParserResult->getMetaData()->getMusic(), ContributionType::MUSIC, $song);

        // store lyrics
        $this->lyricService->storeLyricsForSong($song, $songParserResult->getLyrics());

        // store album
        foreach ($songParserResult->getAlbumMetaData() as $albumMetaData) {
            $albumData = AlbumData::fromMetaData($albumMetaData);

            $album = $this->albumService->findByNameAndType($albumMetaData->getName(), $albumMetaData->getType());

            if ($album === null) {
                $album = $this->albumService->createByData($albumData);

                // trigger album parsing (this will add track numbers)
                $albumParseMessage = new ParseAlbumMessage($albumMetaData);
                $this->messageBus->dispatch($albumParseMessage);
            }

            $rawData = $album->getRawTrackData();
            $trackNumber = -1;
            if ($rawData !== null){
                $trackNumber = $this->getTrackNumber($rawData, $song->getName());
            }

            // create track for album
            $trackData = new TrackData();
            $trackData->setNumber($trackNumber);
            $trackData->setSong($song);
            $trackData->setAlbum($album);

            $this->trackService->createByData($trackData);
        }

        $this->entityManager->flush();
    }

    public function importAlbum(AlbumMetaData $metaData): void
    {
        $album = $this->albumService->findByNameAndType($metaData->getName(), $metaData->getType());
        $rawTrackList = $album->getRawTrackData();

        // get album infos if not stored yet
        if ($rawTrackList === null) {
            $albumParserResult = $this->parser->parseAlbumInformation($metaData->getDetailUrl());
            $rawTrackList = $albumParserResult->getTrackList();

            $updateData = AlbumData::initFromEntity($album);
            $updateData->setRawTrackData($rawTrackList);
            $updateData->setAmountOfTracks(count($rawTrackList));
            $updateData->setCoverImageUrl($albumParserResult->getImageLink());

            $this->albumService->update($album, $updateData, true);
        }

        // check if there are non connected tracks
        $existingAlbumTracks = $album->getTracks();
        foreach ($existingAlbumTracks as $track) {
            if ($track->getNumber() === -1) {
                $trackNumber = $this->getTrackNumber($rawTrackList, $track->getSong()->getName());

                $trackUpdateData = TrackData::initFromEntity($track);
                $trackUpdateData->setNumber($trackNumber);

                $this->trackService->update($track, $trackUpdateData);
            }
        }
        $this->entityManager->flush();

        // store the album image
    }

    /**
     * @param array<string> $names
     */
    private function storeContribution(array $names, ContributionType $contributionType, Song $song): void
    {
        foreach ($names as $name) {
            $godType = GodName::tryFrom($name);

            $god = null;
            // store new if not bela farin or rod #sahnie
            if ($godType === null) {
                $god = $this->godService->findNonGodByName($name);
                if ($god === null) {
                    $god = $this->godService->storeNonGod($name);
                }
            }

            if ($god === null) {
                $god = $this->godService->findGodByName($godType);
            }

            $contributionData = new ContributionData();
            $contributionData->setGod($god);
            $contributionData->setContributionType($contributionType);
            $contributionData->setSong($song);

            $this->contributionService->createByData($contributionData);
        }
    }

    /**
     * @param array<int, string> $rawTrackList
     */
    private function getTrackNumber(array $rawTrackList, string $trackName): int
    {
        $trackNumber = array_key_first(
            array_filter(
                $rawTrackList,
                function ($value) use ($trackName) {
                    return ($value === $trackName);
                }
            )
        );
        return (int) $trackNumber;
    }
}
