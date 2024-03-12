<?php

namespace App\Tests;

use App\Repository\PlantRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GardenTest extends WebTestCase
{
    public function testGetGarden(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/garden/1');

        $this->assertResponseIsSuccessful();
    }

    public function testCreatePlant(): void
    {
        $client = static::createClient();

        $plantRepository = static::getContainer()->get(PlantRepository::class);

        $currentPlants = $plantRepository->findAll();
        $currentPlantsNumber = count($currentPlants);

        $request = $client->jsonRequest(
            'POST', 
            'http://localhost:8080/api/plants',  
            ['genre' => '1',
            'garden' => '1']
        );

        $this->assertResponseIsSuccessful();
        
        $newPlants = $plantRepository->findAll();
        $newPlantsNumber = count($newPlants);
        
        $this->assertSame($currentPlantsNumber + 1, $newPlantsNumber);

    }
}
