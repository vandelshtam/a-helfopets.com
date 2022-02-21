<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpenWeatherController extends AbstractController
{
    #[Route('/open/weather', name: 'open_weather')]
    public function openWeather()
    {
        $apiData = file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat=36.9009641&lon=30.6954846&appid=748bbb696f45a6708c083ef5c839a5dc");
        $weatherArrey = json_decode($apiData,true);
        return $weatherArrey;
    }
    
        // return $this->render('open_weather/index.html.twig', [
        //     'controller_name' => 'OpenWeatherController',
        // ]);
    
}
