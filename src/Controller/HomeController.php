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
        try {
            $response = $this->client->request('GET', 'http://127.0.0.1:8000/search/', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'query' => 'un avion qui crash sur une île',

                ],
            ]);

            $apiResults = $response->toArray();

            $series = $apiResults['series'];
        }catch (\Exception $e) {
            $series = [];
        }


        return $this->render('home/index.html.twig', [
            'series' => $series,
        ]);
    }
}
