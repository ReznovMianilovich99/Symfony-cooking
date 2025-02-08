<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column]
    private ?int $cookingtime = null;

    #[ORM\Column(length: 255)]
    private ?string $linkimage = null;

    #[ORM\OneToOne(mappedBy: 'idplat', cascade: ['persist', 'remove'])]
    private ?Recette $idplat = null;

    /**
     * @var Collection<int, Historique>
     */
    #[ORM\OneToMany(targetEntity: Historique::class, mappedBy: 'idplat')]
    private Collection $idhisto;

    public function __construct()
    {
        $this->idhisto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getCookingtime(): ?int
    {
        return $this->cookingtime;
    }

    public function setCookingtime(int $cookingtime): static
    {
        $this->cookingtime = $cookingtime;
        return $this;
    }

    public function getLinkimage(): ?string
    {
        return $this->linkimage;
    }

    public function setLinkimage(string $linkimage): static
    {
        $this->linkimage = $linkimage;
        return $this;
    }

    public function getIdplat(): ?Recette
    {
        return $this->idplat;
    }

    public function setIdplat(Recette $idplat): static
    {
        if ($idplat->getIdplat() !== $this) {
            $idplat->setIdplat($this);
        }
        $this->idplat = $idplat;
        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getIdhisto(): Collection
    {
        return $this->idhisto;
    }

    public function addIdhisto(Historique $idhisto): static
    {
        if (!$this->idhisto->contains($idhisto)) 
        {
            $this->idhisto->add($idhisto);
            $idhisto->setIdplat($this);
        }
        return $this;
    }

    public function removeIdhisto(Historique $idhisto): static
    {
        if ($this->idhisto->removeElement($idhisto)) {
            if ($idhisto->getIdplat() === $this) {
                $idhisto->setIdplat(null);
            }
        }
        return $this;
    }
}