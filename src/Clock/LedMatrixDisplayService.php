<?php

declare(strict_types=1);

namespace App\Clock;

use App\Clock\Content\Setting\SettingService;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LedMatrixDisplayService
{
    private const TEXT_API_URL = '/text';

    public function __construct(
        private readonly SettingService $settingService,
        private readonly HttpClientInterface $client
    ) {
    }

    public function displayStaticText(string $text): void
    {
        // prepare text for display => remove Umlaute and -
        $text = $this->prepareTextForDisplaying($text);

        $ip = $this->settingService->getLedMatrixDisplayIp();

        $this->client->request('GET', $ip . self::TEXT_API_URL . '?text=' . $text . '&variant=static');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function displayScrollingText(string $text): void
    {
        // prepare text for display => remove Umlaute and -
        $text = $this->prepareTextForDisplaying($text);

        $ip = $this->settingService->getLedMatrixDisplayIp();

        $this->client->request('GET', 'http://' . $ip . self::TEXT_API_URL . '?text=' . $text . '&variant=scrolling');
    }

    private function prepareTextForDisplaying(string $text): string
    {
        setlocale(LC_ALL, 'de_DE');
        return iconv('UTF-8', 'ASCII//TRANSLIT', $text);

//        $replacementMap = [
//            'ä' => 'ae',
//            'ö' => 'oe',
//            'ü' => 'ue',
//            'Ä' => 'Ae',
//            'Ö' => 'ae',
//            'Ü' => 'ae',
//            '-' => 'ae',
//        ];
//
//        return str_replace(['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', '-'], ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', ' '], $text);
    }
}
