<?php

namespace App\Controller;

use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\SettingService;
use App\Clock\LyricGameProcessor;
use App\Discography\Content\Album\AlbumService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    )
    {
    }

    #[Route('/', name: 'app_display')]
    public function index(): Response
    {
        $choices = $this->gameSessionService->getCurrenAlbumChoices();

        $correctAlbum = $this->albumService->findById($choices[LyricGameProcessor::CORRECT_ALBUM]);

        $displayMode = $this->settingService->getCurrentAlbumDisplayMode();

        match ($displayMode){
            AlbumDisplayMode::SPLIT => $template = 'display/album_choice_split.html.twig',
            AlbumDisplayMode::CAROUSEL => $template = 'display/album_choice_carousel.html.twig',
            AlbumDisplayMode::LOOSE => $template = 'display/loose.html.twig',
        };

        $trapAlbums = [];
        foreach ($choices[LyricGameProcessor::TRAP_ALBUMS] as $albumId) {
            $trapAlbums[] = $this->albumService->findById($albumId);
        }

        // we have updated
        $this->gameSessionService->setForceDisplayUpdate(false);

        return $this->render($template, [
            'correctAlbum' => $correctAlbum,
            'trapAlbums' => $trapAlbums,
        ]);
    }

    #[Route('/update', name: 'app_display_update')]
    public function update(): Response
    {
        return new JsonResponse(['success' => $this->gameSessionService->isForceDisplayUpdate()]);}
}
