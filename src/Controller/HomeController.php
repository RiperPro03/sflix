<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        try {
            $response = $this->client->request('GET', 'http://127.0.0.1:8000/all_series/', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $apiResults = $response->toArray();

            $series = $apiResults['series'];
        }catch (\Exception $e) {
            $series = [];
        }

//        $series = ['desperate housewives','24','90210','alias','angels','blades','caprica','buffy','chuck','24','90210','alias','angels','blades','caprica','buffy','chuck'];
        return $this->render('home/index.html.twig', [
            'series' => $series,
        ]);
    }

    /**
     * @Route("/search", name="app_search")
     */
    public function search(Request $request): Response
    {
        $searchQuery = $request->query->get('query', '');

        $series = [];

        if (!empty($searchQuery)) {
            try {
                $response = $this->client->request('GET', 'http://127.0.0.1:8000/search/', [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'query' => $searchQuery, // Utilise le paramètre de recherche récupéré de la requête
                    ],
                ]);

                $apiResults = $response->toArray();
                $series = $apiResults['series'];
            } catch (\Exception $e) {
                $series = [];
            }
        }

        return $this->render('home/index.html.twig', [
            'series' => $series,
        ]);
    }
}
