<?php

namespace App\EventSubscriber;

use App\Service\OpenWeatherApi;
use Symfony\Component\Mime\Email;
use App\Repository\PlantRepository;
use App\Repository\WeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

class PlantEvolutionSubscriber implements EventSubscriberInterface
{
    /**
     * Repository Weather
     *
     * @var WeatherRepository
     */
    private $weatherRepository;

    /**
     * Repository Plant
     *
     * @var PlantRepository
     */
    private $plantRepository;

    private $entityManager;

    private $openWeatherApi;

    private $mailer;

    public function __construct(WeatherRepository $weatherRepository, PlantRepository $plantRepository, EntityManagerInterface $entityManager, OpenWeatherApi $openWeatherApi, MailerInterface $mailer)
    {
        $this->weatherRepository = $weatherRepository;
        $this->plantRepository = $plantRepository;
        $this->entityManager = $entityManager;
        $this->openWeatherApi = $openWeatherApi;
        $this->mailer = $mailer;
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        //? TO DO, get a better way to choose the method instead of the controller ?
        // Getting the controller and see if it's one the showing the garden
        $controller = $event->getController();

        if (is_array($controller)){$controller = $controller[0];}

        $controllerName = get_class($controller);
        
        // If it's not the one showing the garden, we don't do anything
        if (strpos($controllerName, 'App\Controller\APIGardenController') === false){
            return;
        }

        $apiWeather = $this->openWeatherApi->fetch();

        if($apiWeather)
        {
            $dayWeather = $apiWeather;
        }

        else
        {
            $dayWeather = $this->weatherRepository->findOneBy([],['id' => 'desc'])->getDayWeather();
        }

        $dayAgeValue = 0;
        $dayHydrationValue = 0;

        // //! Just to try with clouds
        // $dayWeather = 'Clouds';

        // setting age and hydration evolution depending the weather
        switch ($dayWeather) {
            case 'Clear':
                $dayAgeValue = 1;
                $dayHydrationValue = -2;
                break;
            case 'Clouds':
                $dayAgeValue = rand(0,1);
                $dayHydrationValue = -1;
                break;
            case 'Rain' || 'Drizzle':
                $dayAgeValue = rand(0,1);
                $dayHydrationValue = +1;
                break;
        }
        

        $argument = $event->getArguments();

        if (is_array($argument)){$argument = $argument[0];}

        $garden = $argument;

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
                $this->plantRepository->remove($plant,true);
            }

            // then change picture if needed
            //? TO DO

            // then give value to the user if stage 
            if($olderPlant->getAge() == 8)
            {
                $garden->getUser()->setPoints($garden->getUser()->getPoints() + $plant->getGenre()->getValue());
            }

            // Updating plant's hydration
            $newHydrationPlant = $plant->setHydration($plant->getHydration() + $dayHydrationValue);

            if($newHydrationPlant->getHydration() <= 2 && !isset($email))
            {
            // Sending an email if the plant hydration value is 2 or less
            $email = (new Email())
            ->from('hello@example.com')
            ->to($garden->getUser()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Attention ' . $garden->getUser()->getName() . ', tes plantes sont en train de faner !')
            ->text($garden->getUser()->getName() . ', tes plantes sont en train de faner ! Reviens vite t\'occuper de ton jardin pour que qu\'il reste beau et continuer à aquérir des points.')
            ->html('<p>See Twig integration for better HTML integration!</p>');

            $this->mailer->send($email);
            }

            // then remove if 0
            if($newHydrationPlant->getHydration() <= 0)
            {
                $this->plantRepository->remove($plant,true);
            }

            // then change picture if needed
            //? TO DO

        }

        $this->entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller_arguments' => 'onKernelControllerArguments',
        ];
    }
}
