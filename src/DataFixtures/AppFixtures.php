<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Plant;
use App\Entity\Garden;
use DateTimeImmutable;
use App\Entity\Weather;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $connexion;
    private $hasher;

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher)
    {
        $this->connexion = $connexion;
        $this->hasher = $hasher;
    }

    private function truncate()
    {
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        $this->connexion->executeQuery('TRUNCATE TABLE user');
        $this->connexion->executeQuery('TRUNCATE TABLE garden');
        $this->connexion->executeQuery('TRUNCATE TABLE plant');
        $this->connexion->executeQuery('TRUNCATE TABLE genre');
        $this->connexion->executeQuery('TRUNCATE TABLE weather');
    }
    
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->truncate();

        /************* Weather *************/
        // Making of data fixtures for Weather Entity :

            $weathers = [
                [
                    'date' => '01/02/2024',
                    'dayWeather' => 'Sunny',
                    'forcastDay1' => 'Cloudy',
                    'forcastDay2' => 'Rainy',
                    'forcastDay3' => 'Sunny',
                ],
                [
                    'date' => '02/02/2024',
                    'dayWeather' => 'Cloudy',
                    'forcastDay1' => 'Rainy',
                    'forcastDay2' => 'Sunny',
                    'forcastDay3' => 'Cloudy',
                ],   
                [
                    'date' => '03/02/2024',
                    'dayWeather' => 'Rainy',
                    'forcastDay1' => 'Sunny',
                    'forcastDay2' => 'Cloudy',
                    'forcastDay3' => 'Rainy',
                ],
                [
                    'date' => '04/02/2024',
                    'dayWeather' => 'Sunny',
                    'forcastDay1' => 'Cloudy',
                    'forcastDay2' => 'Rainy',
                    'forcastDay3' => 'Sunny',
                ],
            ];
            
    
            foreach ($weathers as $currentWeather)
            {
                $newWeather = new Weather();

                $newWeather->setDate(new DateTime($currentWeather['date']));
                $newWeather->setDayWeather($currentWeather['dayWeather']);
                $newWeather->setForcastDay1($currentWeather['forcastDay1']);
                $newWeather->setForecastDay2($currentWeather['forcastDay2']);
                $newWeather->setForecastDay3($currentWeather['forcastDay3']);
    
                $manager->persist($newWeather);
    
            }

        /************* Genre *************/
        // Making of data fixtures for Genre Entity :

        $genres = [
            [
                'name' => 'Tulipe',
                'maxHydration' => '5',
                'value' => '1',
            ],
            [
                'name' => 'Jonquille',
                'maxHydration' => '5',
                'value' => '1',
            ],      
            [
                'name' => 'Jacynthe',
                'maxHydration' => '4',
                'value' => '2',
            ]
        ];
        
        $allGenreEntity = [];

        foreach ($genres as $currentGenre)
        {
            $newGenre = new Genre();
            $newGenre->setName($currentGenre['name']);
            $newGenre->setMaxHydration($currentGenre['maxHydration']);
            $newGenre->setvalue($currentGenre['value']);

            $manager->persist($newGenre);

            $allGenreEntity[] =$newGenre;
        }

        /************* User *************/
        // Making of data fixtures for User Entity :

        $users = [
            [
                'name' => 'Romain',
                'email' => 'romain@romain.com',
                'password' => 'romain',
                'roles' => 'ROLE_ADMIN', 
            ],
            [
                'name' => 'Guillaume',
                'email' => 'guillaume@guillaume.com',
                'password' => 'guillaume',
                'roles' => 'ROLE_ADMIN',
            ],
            [
                'name' => 'Maxime',
                'email' => 'maxime@maxime.com',
                'password' => 'maxime',
                'roles' => 'ROLE_ADMIN',
            ],
            [
                'name' => 'Edouard',
                'email' => 'edouard@edouard.com',
                'password' => 'edouard',
                'roles' => 'ROLE_ADMIN',
            ],
            [
                'name' => 'Audrey',
                'email' => 'audrey@audrey.com',
                'password' => 'audrey',
                'roles' => 'ROLE_ADMIN',
            ],
            [
                'name' => 'Mickael',
                'email' => 'mickael@mickael.com',
                'password' => 'mickael',
                'roles' => 'ROLE_ADMIN',
            ],
            [
                'name' => 'Anne',
                'email' => 'anne@anne.com',
                'password' => 'anne',
                'roles' => 'ROLE_USER',
            ]
        ];

        foreach ($users as $currentUser)
        {

            $newUser = new User();
            $newUser->setName($currentUser['name']);
            $newUser->setEmail($currentUser['email']);

            $hashedPassword = $this->hasher->hashPassword(
                $newUser,
                $currentUser['password']
            );
            $newUser->setPassword($hashedPassword);
            $newUser->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
            $newUser->setRoles([$currentUser['roles']]);
            $newUser->setPoints(0);

            /************* Garden *************/
            // Making of data fixtures for Garden Entity :

                $newGarden = new Garden();

                $newGarden->setName('Garden of ' . $currentUser['name']);
                $newGarden->setUser($newUser);

                $manager->persist($newGarden);


                /************* Plant *************/
                // Making of data fixtures for Garden Entity :

                    for ($i = 0; $i < rand(1, 5); $i++)
                    {
                        $newPlant = new Plant();

                        $newPlant->setAge(rand(0,8));
                        $newPlant->setGenre($allGenreEntity[rand(0,2)]);
                        $newPlant->setHydration($newPlant->getGenre()->getMaxHydration());
                        $newPlant->setGarden($newGarden);
                        $newPlant->setCreatedAt(new DateTimeImmutable('now'));
    
                        $manager->persist($newPlant);
                    }

            $manager->persist($newUser);
        }


        $manager->flush();
    }
}
