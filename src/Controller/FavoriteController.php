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
        return $this->render('favorite/favorite.html.twig', [
            'controller_name' => 'FavoriteController',
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
