<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/search/', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => [
                'query' => 'un avion qui crash sur une île',
                'top_n' => 20,
            ],
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            dump("Error occurred with status code: " . $statusCode);
        }

        $apiResults = $response->toArray();

        $series = $apiResults['series'];

        return $this->render('home/index.html.twig', [
            'series' => $series,
        ]);
    }
}
