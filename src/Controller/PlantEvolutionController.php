<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Service\OpenWeatherApi;
use App\Repository\PlantRepository;
use App\Repository\WeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlantEvolutionController extends AbstractController
{
    /**
     * @Route("/api/evolution/garden/{id}", name="api_evolution_garden", methods={"GET"})
     */
    public function updatingPlantEvolution(EntityManagerInterface $entityManager, Garden $garden, PlantRepository $plantRepository, WeatherRepository $weatherRepository, MailerInterface $mailer, OpenWeatherApi $openWeatherApi): Response
    {

        $apiWeather = $openWeatherApi->fetch();


        if($apiWeather)
        {
            $dayWeather = $apiWeather;
        }

        else
        {
            $dayWeather = $weatherRepository->findOneBy([],['id' => 'desc'])->getDayWeather();

        }
        
        $dayAgeValue = 0;
        $dayHydrationValue = 0;

        // setting age and hydration evolution depending the weather
        switch ($dayWeather) {
            case 'Clear':
                $dayAgeValue = 1;
                $dayHydrationValue = -2;
                break;
            case 'Clouds' || 'Mist':
                $dayAgeValue = rand(0,1);
                $dayHydrationValue = -1;
                break;
            case 'Rain' || 'Drizzle':
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

            // then change the picture of the plant 
            if ($olderPlant->getAge() === 0)
            {
                $olderPlant->setPicture('soil.png');
            }
            else if ($olderPlant->getAge() < 6)
            {
                $olderPlant->setPicture('sprout.jpg');
            }
            else if ($olderPlant->getAge() < 8)
            {
                $olderPlant->setPicture($olderPlant->getGenre()->getMaturePicture());
            }
            else if ($olderPlant->getAge() < 10)
            {
                $olderPlant->setPicture($olderPlant->getGenre()->getFlowerPicture());
            }
            else
            {
                $olderPlant->setPicture($olderPlant->getGenre()->getWitheringPicture());
            }

            // then give value to the user if stage 8
            if($olderPlant->getAge() == 8 && ($olderPlant->getAge() != $plant->getAge()))
            {
                $garden->getUser()->setPoints($garden->getUser()->getPoints() + $plant->getGenre()->getValue());
            }

            // Updating plant's hydration
            $newHydrationPlant = $plant->setHydration($plant->getHydration() + $dayHydrationValue);

            if($newHydrationPlant->getHydration() <= 2 && !isset($email))
            {
            // Sending an email if the plant hydration value is 2 or less
            $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($garden->getUser()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Attention ' . $garden->getUser()->getName() . ', tes plantes sont en train de faner !')
            // ->text($garden->getUser()->getName() . ', tes plantes sont en train de faner ! Reviens vite t\'occuper de ton jardin pour que qu\'il reste beau et continuer à aquérir des points.')
            ->htmlTemplate('emails/plantEvolution.html.twig')

            // pass variables (name => value) to the template
            ->context([
            'garden' => $garden,
            ]);

            //? limit for the service reached for now, available again next month
            // $mailer->send($email);
            }

            // then remove if 0
            if($newHydrationPlant->getHydration() <= 0)
            {
                $plantRepository->remove($plant,true);
            }

        }

        $entityManager->flush();

        return $this->json(
            $garden,
            Response::HTTP_OK,
            [],
            ['groups' => ['show_garden']]
        );
    }
}
