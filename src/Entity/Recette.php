<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'idplat', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $idplat = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recettes')]
    private Collection $idingredient;

    public function __construct()
    {
        $this->idingredient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdplat(): ?Plat
    {
        return $this->idplat;
    }

    public function setIdplat(Plat $idplat): static
    {
        $this->idplat = $idplat;
        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIdingredient(): Collection
    {
        return $this->idingredient;
    }

    public function addIdingredient(Ingredient $idingredient): static
    {
        if (!$this->idingredient->contains($idingredient))
        {
            $this->idingredient->add($idingredient);
        }
        return $this;
    }

    public function removeIdingredient(Ingredient $idingredient): static
    {
        $this->idingredient->removeElement($idingredient);
        return $this;
    }
}