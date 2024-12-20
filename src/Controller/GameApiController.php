<?php

namespace App\Controller;

use App\Clock\Content\GameRound\GameRoundService;
use App\Clock\LyricGameProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/api')]

class GameApiController extends AbstractController
{
    public function __construct(private LyricGameProcessor $lyricGameProcessor)
    {
    }

    #[Route('/', name: 'app_game_api_button_choice')]
    public function index(Request $request, GameRoundService $gameRoundService): Response
    {
        $buttonName = $request->get('buttonName');
        if ($buttonName === null) {
            return new Response('Missing parameter buttonName', 422);
        }
        if (!array_key_exists($buttonName, LyricGameProcessor::BUTTON_TO_GOD_MAPPING)){
            return new Response('Not allowed value for parameter buttonName', 422);
        }
        try {
            $gameRoundService->findCurrentRound();
        } catch (\Exception $e) {
            return new Response('No game round running', 500);
        }


        $audio = $this->lyricGameProcessor->evaluateButtonChoice($buttonName);
        return new JsonResponse(['audio_path' => $audio->getFile()->getRelativePath()]);
    }
}
