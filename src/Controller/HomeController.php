<?php

namespace App\Controller;

use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
use http\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $client;
    private String $urlSearchAPI = 'http://127.0.0.1:8000/';

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
            $response = $this->client->request('GET', $this->urlSearchAPI . 'all_series/', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $apiResults = $response->toArray();

            $series = $apiResults['series'];
        }catch (\Exception $e) {
            $series = [];
        }

        $likedSeries = $this->getLikedSeries();

        return $this->render('home/index.html.twig', [
            'series' => $series,
            'likedSeries' => $likedSeries,
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
                $response = $this->client->request('GET', $this->urlSearchAPI . 'search/', [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'query' => $searchQuery,
                    ],
                ]);

                $apiResults = $response->toArray();
                $series = $apiResults['series'];
            } catch (\Exception $e) {
                $series = [];
            }
        }

        $likedSeries = $this->getLikedSeries();

        return $this->render('home/index.html.twig', [
            'series' => $series,
            'likedSeries' => $likedSeries,
        ]);
    }

    /**
     * @Route("/toggle-like/{title}", name="app_toggle_like", methods={"POST"})
     */
    public function toggleLike(string $title, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        }

        $serieRepository = $em->getRepository(Serie::class);
        $serie = $serieRepository->findOneBy(['title' => $title]);

        // Création de la série si elle n'existe pas
        if (!$serie) {
            $serie = new Serie();
            $serie->setTitle($title);
            $serie->setImg('img-series/' . urlencode($title) . '.jpg');
            $em->persist($serie);
        }

        // Toggle like ou dislike
        if ($user->getSeries()->contains($serie)) {
            $user->removeSeries($serie);
            $liked = false;
        } else {
            $user->addSeries($serie);
            $liked = true;
        }
        // Sauvegarde en base de données
        $em->flush();

        return $this->json(['success' => true, 'liked' => $liked]);
    }

    /**
     * @return array
     */
    private function getLikedSeries(): array
    {
        $user = $this->getUser();
        $likedSeries = [];

        if ($user) {
            $likedSeriesTitles = $user->getSeries()->map(function ($serie) {
                return json_decode('"' . $serie->getTitle() . '"');
            })->toArray();

            foreach ($likedSeriesTitles as $title) {
                $likedSeries[$title] = true;
            }
        }
        return $likedSeries;
    }

}
