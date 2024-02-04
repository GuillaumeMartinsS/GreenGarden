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
        
        
        $plants = $garden->getPlants();

        foreach($plants as $plant)
        {
            // Updating plant's age
            $olderPlant = $plant->setAge($plant->getAge() + 1);

            // then remove if 10+
            if($olderPlant->getAge() > 10)
            {
                $plantRepository->remove($plant,true);
            }

            // then change picture if needed
            //? TO DO

            // then give value to the user if stage 8
            if($olderPlant->getAge() == 8)
            {
                $garden->getUser()->setPoints($garden->getUser()->getPoints() + $plant->getGenre()->getValue());
            }

            // Updating plant's hydration
            $newHydrationPlant = $plant->setHydration($plant->getHydration() - 1);

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
