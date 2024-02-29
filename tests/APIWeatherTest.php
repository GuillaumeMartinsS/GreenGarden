<?php

namespace App\Tests;

use App\Service\OpenWeatherApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class APIWeatherTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        // getting the service OpenWeatherAPI
        $openWeatherApi = static::getContainer()->get(OpenWeatherApi::class);

        // we use the API to get the current weather
        $apiWeather = $openWeatherApi->fetch();

        $weatherPossibleValues = ['Rain', 'Drizzle', 'Mist', 'Clouds', 'Clear'];

        // testing if we get the actual weather and if it's a value within $weatherPossibleValues
        $this->assertContains($apiWeather, $weatherPossibleValues);

        // $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
