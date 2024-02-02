<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxHydration;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $maturePicture = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $flowerPicture = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxHydration(): ?int
    {
        return $this->maxHydration;
    }

    public function setMaxHydration(int $maxHydration): self
    {
        $this->maxHydration = $maxHydration;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getMaturePicture(): ?array
    {
        return $this->maturePicture;
    }

    public function setMaturePicture(?array $maturePicture): self
    {
        $this->maturePicture = $maturePicture;

        return $this;
    }

    public function getFlowerPicture(): ?array
    {
        return $this->flowerPicture;
    }

    public function setFlowerPicture(?array $flowerPicture): self
    {
        $this->flowerPicture = $flowerPicture;

        return $this;
    }
}
