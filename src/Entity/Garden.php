<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GardenRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GardenRepository::class)
 */
class Garden
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_garden"})
     * @Groups({"show_plant"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_garden"})
     * @Groups({"show_garden"})
     * 
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gardens")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_garden"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="garden", orphanRemoval=true)
     * @Groups({"show_garden"})
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // /**
    //  * @return Collection<int, Plant>
    //  */
    // public function getPlants(): Collection
    // {
    //     return $this->plants;
    // }

    /**
     * @return Collection<int, Plant>
     * ?! this is a modified version of the above one, to be sure it always returns an array, never an objet
     * ?! see issue here : https://github.com/symfony/symfony/issues/36965
     * ?! and direct answer there : https://api-platform.com/docs/core/serialization/#collection-relation 
     */
    public function getPlants()
    {
        return $this->plants->getValues();
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants[] = $plant;
            $plant->setGarden($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getGarden() === $this) {
                $plant->setGarden(null);
            }
        }

        return $this;
    }
}
