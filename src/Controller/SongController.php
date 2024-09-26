<?php

namespace App\Controller;

use App\Discography\Content\Song\SongService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/song')]
class SongController extends AbstractController
{
    #[Route('/list', name: 'app_song_list')]
    public function index(SongService $songService): Response
    {
        $songs = $songService->findAll();

        return $this->render('song/list.html.twig', [
            'songs' => $songs,
        ]);
    }
}
