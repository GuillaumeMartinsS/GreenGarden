<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeatherRepository::class)
 */
class Weather
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dayWeather;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forcastDay1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forecastDay2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forecastDay3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDayWeather(): ?string
    {
        return $this->dayWeather;
    }

    public function setDayWeather(string $dayWeather): self
    {
        $this->dayWeather = $dayWeather;

        return $this;
    }

    public function getForcastDay1(): ?string
    {
        return $this->forcastDay1;
    }

    public function setForcastDay1(string $forcastDay1): self
    {
        $this->forcastDay1 = $forcastDay1;

        return $this;
    }

    public function getForecastDay2(): ?string
    {
        return $this->forecastDay2;
    }

    public function setForecastDay2(string $forecastDay2): self
    {
        $this->forecastDay2 = $forecastDay2;

        return $this;
    }

    public function getForecastDay3(): ?string
    {
        return $this->forecastDay3;
    }

    public function setForecastDay3(string $forecastDay3): self
    {
        $this->forecastDay3 = $forecastDay3;

        return $this;
    }
}
