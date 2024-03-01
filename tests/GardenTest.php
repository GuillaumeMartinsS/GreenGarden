<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GardenTest extends WebTestCase
{
    public function testGarden(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/garden/1');

        $this->assertResponseIsSuccessful();
    }
}
