<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_garden"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_garden"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_garden"})
     */
    private $maxHydration;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_garden"})
     */
    private $value;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"show_garden"})
     */
    private $maturePicture = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"show_garden"})
     */
    private $flowerPicture = [];

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="genre", orphanRemoval=true)
     */
    private $plants;

    public function __construct()
    {
        $this->plants = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Plant>
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants[] = $plant;
            $plant->setGenre($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getGenre() === $this) {
                $plant->setGenre(null);
            }
        }

        return $this;
    }
}
