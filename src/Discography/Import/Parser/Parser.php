<?php

declare(strict_types=1);

namespace App\Discography\Import\Parser;

use App\Discography\Content\Album\ReleaseType;
use Exception;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Parser
{
    public const BASE_URL = 'https://www.bademeister.com';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function crawlAlbumImage(string $url): string
    {
        $response = $this->client->request('GET', self::BASE_URL . $url);

        return $response->getContent();
    }

    /**
     * @return array<string>
     */
    public function parseSongList(): array
    {
        $response = $this->client->request('GET', self::BASE_URL . '/songs');

        $crawler = new Crawler($response->getContent());
        $links = $crawler->filter('[class*=Songs_link]')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        return $links;
    }

    /**
     * @throws Exception
     */
    public function parseSongInformation(string $url): SongParserResult
    {
        $response = $this->client->request('GET', self::BASE_URL . $url);
        $crawler = new Crawler($response->getContent());

        $songResponse = new SongParserResult();

        // crawl title
        $titleElement = $crawler->filter('[class*=Song_h1]');
        if ($titleElement->count() === 0) {
            throw new Exception($url . ' contains no title');
        }
        $songResponse->setTitle(trim($titleElement->getNode(0)->nodeValue));

        // crawl music and text
        $metaElements = $crawler->filter('[class*=Song_meta]');
        $songMetaData = new SongMetaData();
        foreach ($metaElements as $item) {
            $value = $item->nodeValue;
            if (str_starts_with($value, 'Musik')) {
                $value = str_replace('Musik: ', '', $value);
                $songMetaData->setMusic(explode(' / ', $value));
            }
            if (str_starts_with($value, 'Text')) {
                $value = str_replace('Text: ', '', $value);
                $songMetaData->setText(explode(' / ', $value));
            }
        }
        $songResponse->setMetaData($songMetaData);

        // crawl album metadata
        $itemContentElement = $crawler->filter('[class*=Song_itemContent]');
        if ($itemContentElement->count() === 0) {
            throw new Exception($url . ' contains no album');
        }

        $albumMetaDataContainer = [];
        foreach ($itemContentElement as $item){
            preg_match('~(.*?)\(([0-9]*?)\)(.*)~', $item->nodeValue, $matches);
            $albumMetaData = new AlbumMetaData();
            $type = ReleaseType::tryFrom(strtolower(trim($matches[1])));
            if ($type === null){
                $type = ReleaseType::OTHER;
            }

            $albumMetaData->setType($type);
            $albumMetaData->setReleaseYear((int) $matches[2]);
            $albumMetaData->setName($matches[3]);

            $albumMetaDataContainer[] = $albumMetaData;
        }

        // crawl detail url
        $detailLinkElement = $crawler->filter('[class*=Song_link]');
        if ($detailLinkElement->count() === 0) {
            throw new Exception($url . ' contains no song detail url');
        }
        foreach ($detailLinkElement as $itemNr => $item){
            $href = new Crawler($item);
            $albumMetaDataContainer[$itemNr]->setDetailUrl($href->filter('a')->attr('href'));
        }

        $songResponse->setAlbumMetaData($albumMetaDataContainer);

        // crawl lyrics
        $lyricsElement = $crawler->filter('[class*=Song_lyrics]');

        $lines = [];
        if ($lyricsElement->count() > 0){
            $itemCrawler = new Crawler($lyricsElement->getNode(0));

            // explode the HTML content on <br> or </p> to get individual lines, trim to remove any leading/trailing whitespaces
            // Method html() is used instead of text() here to preserve <br> and <p>
            $lines = array_map(function ($line) {
                // Remove the <p> tag from the line
                return trim(preg_replace('/<p>/', '', $line));
            }, preg_split("/<\/p>|<br>/", $itemCrawler->html()));

            $lines = array_filter($lines, 'strlen');
        }
        $songResponse->setLyrics($lines);

        return $songResponse;
    }

    public function parseAlbumInformation(string $url): AlbumParserResult
    {
        $response = $this->client->request('GET', self::BASE_URL . $url);

        $crawler = new Crawler($response->getContent());

        $trackNumberElements = $crawler->filter('[class*=Tracklist_trackNumber]');
        $tracksNumbers = [];
        foreach ($trackNumberElements as $item) {
            $tracksNumbers[] = trim($item->nodeValue);
        }

        $linkElements = $crawler->filter('[class*=Tracklist_link]');
        $tracks = [];
        foreach ($linkElements as $trackNumber => $item) {
            $tracks[$tracksNumbers[$trackNumber]] = trim($item->nodeValue);
        }

        $result = new AlbumParserResult();
        $result->setTrackList($tracks);

        $url = $crawler->filter('[class*=DiscographyItem_coverImage]')->first()->attr('src');
        $result->setImageLink($url);

        return $result;
    }
}
