<?php

namespace App\Entity;

use App\Repository\AnimalsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalsRepository::class)]
class Animals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $species = null;

    #[ORM\Column]
    private array $img = [];

    /**
     * @var Collection<int, RapportAnimal>
     */
    #[ORM\OneToMany(targetEntity: RapportAnimal::class, mappedBy: 'animal')]
    private Collection $rapportAnimals;

    /**
     * @var Collection<int, RapportNourriture>
     */
    #[ORM\OneToMany(targetEntity: RapportNourriture::class, mappedBy: 'animal')]
    private Collection $rapportNourritures;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Habitat $habitat = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->rapportAnimals = new ArrayCollection();
        $this->rapportNourritures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getImg(): array
    {
        return $this->img;
    }

    public function setImg(array $img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, RapportAnimal>
     */
    public function getRapportAnimals(): Collection
    {
        return $this->rapportAnimals;
    }

    public function addRapportAnimal(RapportAnimal $rapportAnimal): static
    {
        if (!$this->rapportAnimals->contains($rapportAnimal)) {
            $this->rapportAnimals->add($rapportAnimal);
            $rapportAnimal->setAnimal($this);
        }

        return $this;
    }

    public function removeRapportAnimal(RapportAnimal $rapportAnimal): static
    {
        if ($this->rapportAnimals->removeElement($rapportAnimal)) {
            // set the owning side to null (unless already changed)
            if ($rapportAnimal->getAnimal() === $this) {
                $rapportAnimal->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RapportNourriture>
     */
    public function getRapportNourritures(): Collection
    {
        return $this->rapportNourritures;
    }

    public function addRapportNourriture(RapportNourriture $rapportNourriture): static
    {
        if (!$this->rapportNourritures->contains($rapportNourriture)) {
            $this->rapportNourritures->add($rapportNourriture);
            $rapportNourriture->setAnimal($this);
        }

        return $this;
    }

    public function removeRapportNourriture(RapportNourriture $rapportNourriture): static
    {
        if ($this->rapportNourritures->removeElement($rapportNourriture)) {
            // set the owning side to null (unless already changed)
            if ($rapportNourriture->getAnimal() === $this) {
                $rapportNourriture->setAnimal(null);
            }
        }

        return $this;
    }
    public function __toString():string
    {
        return $this->getName();
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
