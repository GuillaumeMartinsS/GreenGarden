<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherApi
{

    // HttpClient to consume APIs and supports synchronous and asynchronous operations.
    private $httpClient;

    // to get OpenWeatherAPI key from services.yaml
    private $parameterBag;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->httpClient = $httpClient;
        $this->parameterBag = $parameterBag;
    }

    public function fetch()
    {
        $response = $this->httpClient->request(
            'GET',
            // API url for Paris Weather
            'https://api.openweathermap.org/data/2.5/weather?lat=48.86&lon=2.33&',
            [
                'query' => [
                    // to get the API key
                    'apiKey' => $this->parameterBag->get('app.openweather_api_key'),
                ]
            ]
        );

        // To decode response
        $responseArray = $response->toArray();

        // To get exaclty what we're looking for
        $realWeather = $responseArray['weather']['0']['main'];

        return $realWeather;
    }

}