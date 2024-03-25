<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlantRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PlantRepository::class)
 */
class Plant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_plant"})
     * @Groups({"show_garden"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_plant"})
     * @Groups({"show_garden"})
     */
    private $age;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_plant"})
     * @Groups({"show_garden"})
     */
    private $hydration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"show_plant"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Garden::class, inversedBy="plants")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_plant"})
     */
    private $garden;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="plants")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_plant"})
     * @Groups({"show_garden"})
     */
    private $genre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getHydration(): ?int
    {
        return $this->hydration;
    }

    public function setHydration(int $hydration): self
    {
        $this->hydration = $hydration;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getGarden(): ?Garden
    {
        return $this->garden;
    }

    public function setGarden(?Garden $garden): self
    {
        $this->garden = $garden;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
