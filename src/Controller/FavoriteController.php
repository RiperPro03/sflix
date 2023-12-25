<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FavoriteController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
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
        $user = $this->getUser();

        if ($user) {
            $likedSeriesTitles = $user->getSeries()->map(function ($serie) {
                return json_decode('"' . $serie->getTitle() . '"');
            })->toArray();


        } else {
            $likedSeriesTitles = [];
        }

        $serieRandom = [];
        $serieRandom2=[];

        $nomserieR1 ="";
        $nomserieR2 ="";
        if (count($likedSeriesTitles)>2) {
            $nombreRandom = rand(0 ,count($likedSeriesTitles)-1);
           

            $nomserieR1 = $likedSeriesTitles[$nombreRandom];
            $nomserieR2 = $likedSeriesTitles[($nombreRandom+1)%(count($likedSeriesTitles)-1)];
            
            array_push($serieRandom,$nomserieR1);
            array_push($serieRandom2 ,$nomserieR2);
        }
        

        try {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/similar_series/', [
                'json' => [
                    'series_list' => $likedSeriesTitles,
                ],
            ]);

            $apiResults = $response->toArray();

            $series = $apiResults['series'];
        }catch (\Exception $e) {
            $series = [];
        }

        try {
            $responseR1 = $this->client->request('POST', 'http://127.0.0.1:8000/similar_series/', [
                'json' => [
                    'series_list' => $serieRandom,
                ],
            ]);

            $apiResultsR1 = $responseR1->toArray();

            $serieR1 = $apiResultsR1['series'];
        }catch (\Exception $e) {
            $serieR1 = [];
        }

        try {
            $responseR2 = $this->client->request('POST', 'http://127.0.0.1:8000/similar_series/', [
                'json' => [
                    'series_list' => $serieRandom2,
                ],
            ]);

            $apiResultsR2 = $responseR2->toArray();

            $serieR2 = $apiResultsR2['series'];
        }catch (\Exception $e) {
            $serieR2 = [];
        }


        return $this->render('favorite/suggestion.html.twig', [
            'controller_name' => 'SuggestionController',
            'suggestionSeriesTitles' => $series,
            'serieR1' => array_slice($serieR1,0,6),
            'nomserieR1'=>$nomserieR1,
            'serieR2'=> array_slice($serieR2,0,6),
            'nomserieR2'=>$nomserieR2,
        ]);
    }
}
