<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FavoriteController extends AbstractController
{

    private $client;
    private String $urlSearchAPI;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->urlSearchAPI = $params->get('URL_SearchAPI');
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
        $serieRandom2 = [];
        $nomserieR1 = [];
        $nomserieR2 = [];

        if (count($likedSeriesTitles) > 2) {
            $randomIndices = array_rand($likedSeriesTitles, 2);
            $nomserieR1 = $likedSeriesTitles[$randomIndices[0]];
            $nomserieR2 = $likedSeriesTitles[$randomIndices[1]];

            $serieRandom = [$nomserieR1];
            $serieRandom2 = [$nomserieR2];
        }



        try {
            $response = $this->client->request('POST', $this->urlSearchAPI . 'similar_series/', [
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
            $responseR1 = $this->client->request('POST', $this->urlSearchAPI . 'similar_series/?top_n=6', [
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
            $responseR2 = $this->client->request('POST', $this->urlSearchAPI . 'similar_series/?top_n=6', [
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
            'serieR1' => $serieR1,
            'nomserieR1'=>$nomserieR1,
            'serieR2'=> $serieR2,
            'nomserieR2'=>$nomserieR2,
        ]);
    }
}
