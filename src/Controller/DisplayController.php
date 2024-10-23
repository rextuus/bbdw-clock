<?php

namespace App\Controller;

use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\SettingService;
use App\Clock\LyricGameProcessor;
use App\Discography\Content\Album\AlbumService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/display')]
class DisplayController extends AbstractController
{
    public function __construct(
        private readonly GameSessionService $gameSessionService,
        private readonly AlbumService $albumService,
        private readonly SettingService $settingService,
        #[Autowire('%env(OPENWEATHER_API_KEY)%')] private readonly string $apiKey,
    )
    {
    }

    #[Route('/', name: 'app_display')]
    public function index(): Response
    {
        $choices = $this->gameSessionService->getCurrenAlbumChoices();

        $correctAlbum = $this->albumService->findById($choices[LyricGameProcessor::CORRECT_ALBUM][0]['id']);

        $displayMode = $this->settingService->getCurrentAlbumDisplayMode();

        match ($displayMode){
            AlbumDisplayMode::SPLIT => $template = 'display/album_choice_split.html.twig',
            AlbumDisplayMode::CAROUSEL => $template = 'display/album_choice_carousel.html.twig',
            AlbumDisplayMode::LOOSE => $template = 'display/loose.html.twig',
            AlbumDisplayMode::WIN => $template = 'display/win.html.twig',
        };

        $trapAlbums = [];
        foreach ($choices[LyricGameProcessor::TRAP_ALBUMS] as $album) {
            $trapAlbums[] = $this->albumService->findById($album['id']);
        }

        // get random number between 1 and 5
        $randomNumber = random_int(1, 5);
        $looseImage = 'build/images/loose/dth_' . $randomNumber . '.jpg';

        $randomNumber = random_int(1, 3);
        $winImage = 'build/images/win/win_' . $randomNumber . '.gif';

        // we have updated
        $this->gameSessionService->setForceDisplayUpdate(false);

        return $this->render($template, [
            'correctAlbum' => $correctAlbum,
            'trapAlbums' => $trapAlbums,
            'looseImage' => $looseImage,
            'winImage' => $winImage,
        ]);
    }

    #[Route('/update', name: 'app_display_update')]
    public function update(): Response
    {
        return new JsonResponse(['success' => $this->gameSessionService->isForceDisplayUpdate()]);
    }

    #[Route('/test', name: 'app_display_test')]
    public function test(): Response
    {
        return $this->render('display/weather.html.twig', [
            'openweather_api_key' => $this->apiKey
        ]);
    }
}
