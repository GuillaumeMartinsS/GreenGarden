<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\PlantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GardenTest extends WebTestCase
{
    // we get the user to test if we can access to a garden with the url below while connected
    public function testGetGardenUserConnected(): void
    {
        $client = static::createClient();

        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findBy(['email' => 'guillaume@guillaume.com']);
        $testuser = $user[0];

        $client->loginUser($testuser);

        $crawler = $client->request('GET', '/api/garden/1');

        $this->assertResponseIsSuccessful();
    }

    // We are here testing the connection to a garden with a non-connected user
    public function testGetGardenNoConnected(): void
    {
        $client = static::createClient();

        
        $crawler = $client->request('GET', '/api/garden/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreatePlant(): void
    {
        $client = static::createClient();

        $plantRepository = static::getContainer()->get(PlantRepository::class);

        $currentPlants = $plantRepository->findAll();
        $currentPlantsNumber = count($currentPlants);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findBy(['email' => 'guillaume@guillaume.com']);
        $testuser = $user[0];

        $client->loginUser($testuser);

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
