<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idcommande = null;

    #[ORM\OneToOne(inversedBy: 'iduser', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $iduser = null;

    /**
     * @var Collection<int, Plat>
     */
    #[ORM\ManyToMany(targetEntity: Plat::class)]
    private Collection $listplat;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateheurecommande = null;

    public function __construct()
    {
        $this->listplat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function setIdcommande(int $idcommande): static
    {
        $this->idcommande = $idcommande;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getListplat(): Collection
    {
        return $this->listplat;
    }

    public function addListplat(Plat $listplat): static
    {
        if (!$this->listplat->contains($listplat)) {
            $this->listplat->add($listplat);
        }

        return $this;
    }

    public function removeListplat(Plat $listplat): static
    {
        $this->listplat->removeElement($listplat);

        return $this;
    }

    public function getDateheurecommande(): ?\DateTimeImmutable
    {
        return $this->dateheurecommande;
    }

    public function setDateheurecommande(\DateTimeImmutable $dateheurecommande): static
    {
        $this->dateheurecommande = $dateheurecommande;

        return $this;
    }
}
