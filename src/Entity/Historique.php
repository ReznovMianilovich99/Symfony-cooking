<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idhisto = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Dateheurehisto = null;

    #[ORM\ManyToOne(inversedBy: 'idhisto')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $idplat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdhisto(): ?int
    {
        return $this->idhisto;
    }

    public function setIdhisto(int $idhisto): static
    {
        $this->idhisto = $idhisto;
        return $this;
    }

    public function getDateheurehisto(): ?\DateTimeImmutable
    {
        return $this->Dateheurehisto;
    }

    public function setDateheurehisto(\DateTimeImmutable $Dateheurehisto): static
    {
        $this->Dateheurehisto = $Dateheurehisto;
        return $this;
    }

    public function getIdplat(): ?Plat
    {
        return $this->idplat;
    }

    public function setIdplat(?Plat $idplat): static
    {
        $this->idplat = $idplat;
        return $this;
    }
}