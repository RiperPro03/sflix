<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/favorite", name="app_favorite")
     */
    public function index(): Response
    {

        $user = $this->getUser();

        if ($user) {
            $likedSeries = $user->getSeries()->map(function ($serie) {
                return [
                    'title' => json_decode('"' . $serie->getTitle() . '"'),
                    'img' => str_replace('\u0020', ' ', urldecode($serie->getImg()))
                ];
            })->toArray();

        } else {
            $likedSeries = [];
        }

        return $this->render('favorite/favorite.html.twig', [
            'controller_name' => 'FavoriteController',
            'likedSeries' => $likedSeries,
        ]);
    }

    /**
     * @Route("/suggestion", name="app_suggestion")
     */
    public function suggestion(): Response
    {
        return $this->render('favorite/suggestion.html.twig', [
            'controller_name' => 'FavoriteController',
        ]);
    }
}
