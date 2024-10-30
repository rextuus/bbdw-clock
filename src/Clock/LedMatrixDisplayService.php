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

    // TODO use async events to push => if screen is in scrolling mode we need a huge timeout
    public function displayStaticText(string $text): void
    {
        // prepare text for display => remove Umlaute and -
        $text = $this->prepareTextForDisplaying($text);

        $ip = $this->settingService->getLedMatrixDisplayIp();

        $colors = $this->settingService->getSettings()->getFontColor();
        $color = '&colorR=' . $colors['r'] . '&colorG=' . $colors['g'] . '&colorB=' . $colors['b'];
        try {
            $response = $this->client->request(
                'GET',
                'http://' . $ip . self::TEXT_API_URL . '?text=' . $text . '&variant=static' . $color,
                [
                    'timeout' => 120, // Timeout in seconds (2 minutes)
                ]
            );
        } catch (TransportExceptionInterface $e) {
            // Handle the exception
            echo "Request failed: " . $e->getMessage();
        }

        $this->settingService->setCurrentLedText($text);
    }

    public function displayScrollingText(string $text): void
    {
        // prepare text for display => remove Umlaute and -
        $text = $this->prepareTextForDisplaying($text);

        $ip = $this->settingService->getLedMatrixDisplayIp();

        $colors = $this->settingService->getSettings()->getFontColor();
        $color = '&colorR=' . $colors['r'] . '&colorG=' . $colors['g'] . '&colorB=' . $colors['b'];
        try {
            $response = $this->client->request(
                'GET',
                'http://' . $ip . self::TEXT_API_URL . '?text=' . $text . $color,
                [
                    'timeout' => 120, // Timeout in seconds (2 minutes)
                ]
            );
        } catch (TransportExceptionInterface $e) {
            // Handle the exception
            echo "Request failed: " . $e->getMessage();
        }

        $this->settingService->setCurrentLedText($text);
    }

    private function prepareTextForDisplaying(string $text): string
    {
        setlocale(LC_ALL, 'de_DE');
        return iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    }
}
