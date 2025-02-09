<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2)]
    private ?string $totaleprice = null;

    #[ORM\Column]
    private ?bool $paiementcheck = null;

    #[ORM\Column]
    private ?bool $isready = null;

    #[ORM\Column]
    private ?bool $issend = null;

    public function __construct()
    {
        $this->listplat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotaleprice(): ?string
    {
        return $this->totaleprice;
    }

    public function setTotaleprice(string $totaleprice): static
    {
        $this->totaleprice = $totaleprice;

        return $this;
    }

    public function isPaiementcheck(): ?bool
    {
        return $this->paiementcheck;
    }

    public function setPaiementcheck(bool $paiementcheck): static
    {
        $this->paiementcheck = $paiementcheck;

        return $this;
    }

    public function isready(): ?bool
    {
        return $this->isready;
    }

    public function setIsready(bool $isready): static
    {
        $this->isready = $isready;

        return $this;
    }

    public function issend(): ?bool
    {
        return $this->issend;
    }

    public function setIssend(bool $issend): static
    {
        $this->issend = $issend;

        return $this;
    }
    
}
