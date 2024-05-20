<?php

namespace App\Entity;

use App\Repository\HabitatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $listAnimals = null;

    /**
     * @var Collection<int, CommentHabitat>
     */
    #[ORM\OneToMany(targetEntity: CommentHabitat::class, mappedBy: 'habitat', orphanRemoval: true)]
    private Collection $commentHabitats;

    #[ORM\Column]
    private array $img = [];

    /**
     * @var Collection<int, Animals>
     */
    #[ORM\OneToMany(targetEntity: Animals::class, mappedBy: 'habitat')]
    private Collection $animals;

    public function __construct()
    {
        $this->commentHabitats = new ArrayCollection();
        $this->animals = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getListAnimals(): ?array
    {
        return $this->listAnimals;
    }

    public function setListAnimals(?array $listAnimals): static
    {
        $this->listAnimals = $listAnimals;

        return $this;
    }

    /**
     * @return Collection<int, CommentHabitat>
     */
    public function getCommentHabitats(): Collection
    {
        return $this->commentHabitats;
    }

    public function addCommentHabitat(CommentHabitat $commentHabitat): static
    {
        if (!$this->commentHabitats->contains($commentHabitat)) {
            $this->commentHabitats->add($commentHabitat);
            $commentHabitat->setHabitat($this);
        }

        return $this;
    }

    public function removeCommentHabitat(CommentHabitat $commentHabitat): static
    {
        if ($this->commentHabitats->removeElement($commentHabitat)) {
            // set the owning side to null (unless already changed)
            if ($commentHabitat->getHabitat() === $this) {
                $commentHabitat->setHabitat(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getName();
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
     * @return Collection<int, Animals>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animals $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animals $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }
}
