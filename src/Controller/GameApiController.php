<?php

namespace App\Controller;

use App\Clock\LyricGameProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request): Response
    {
        $buttonName = $request->get('buttonName');
        if ($buttonName === null) {
            return new Response('Missing parameter buttonName', 422);
        }
        if (!array_key_exists($buttonName, LyricGameProcessor::BUTTON_TO_GOD_MAPPING)){
            return new Response('Not allowed value for parameter buttonName', 422);
        }

        $this->lyricGameProcessor->evaluateButtonChoice($buttonName);

        return $this->render('game_api/index.html.twig', [
            'controller_name' => 'GameApiController',
        ]);
    }
}
