<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Repository\PlantRepository;
use App\Repository\WeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlantEvolutionController extends AbstractController
{
    /**
     * @Route("/evolution/garden/{id}", name="api_evolution_garden")
     */
    public function updatingPlantEvolution(EntityManagerInterface $entityManager, Garden $garden, PlantRepository $plantRepository, WeatherRepository $weatherRepository): Response
    {

        $dayWeather = $weatherRepository->findOneBy([],['id' => 'desc'])->getDayWeather();
        $dayAgeValue = 0;
        $dayHydrationValue = 0;


        // setting age and hydration evolution depending the weather
        switch ($dayWeather) {
            case 'Sunny':
                $dayAgeValue = 1;
                $dayHydrationValue = -2;
                break;
            case 'Cloudy':
                $dayAgeValue = rand(0,1);
                $dayHydrationValue = -1;
                break;
            case 'Rainy':
                $dayAgeValue = rand(0,1);
                $dayHydrationValue = +1;
                break;
        }
        
        $plants = $garden->getPlants();

        foreach($plants as $plant)
        {

            // for every plant : looking at the updatedAt, if not the createdAt
            // comparing it to all registered weather datas
            // for every day, look at the weather and increase the plant age depending it.


            // Updating plant's age
            $olderPlant = $plant->setAge($plant->getAge() + $dayAgeValue);

            // then remove if 10+
            if($olderPlant->getAge() > 10)
            {
                $plantRepository->remove($plant,true);
            }

            // then change picture if needed
            //? TO DO

            // then give value to the user if stage 8
            if($olderPlant->getAge() == 8 && $olderPlant->getAge() != $plant->getAge())
            {
                $garden->getUser()->setPoints($garden->getUser()->getPoints() + $plant->getGenre()->getValue());
            }

            // Updating plant's hydration
            $newHydrationPlant = $plant->setHydration($plant->getHydration() + $dayHydrationValue);

            // then remove if 0
            if($newHydrationPlant->getHydration() == 0)
            {
                $plantRepository->remove($plant,true);
            }

            // then change picture if needed
            //? TO DO

        }

        $entityManager->flush();

        return $this->json(
            $garden,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_garden']]
        );
    }
}
