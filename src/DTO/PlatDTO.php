<?php

namespace App\DTO;
use App\Entity\Plat;

class PlatDTO
{
    public ?int $id = null;
    public ?string $nom = null;
    public ?string $prix = null;
    public ?int $cookingtime = null;
    public ?string $linkimage = null;

    public function __construct(Plat $plat)
    {
        // Initialisation des propriétés de base
        $this->id = $plat->getId();
        $this->nom = $plat->getNom();
        $this->prix = $plat->getPrix();
        $this->cookingtime = $plat->getCookingtime();
        $this->linkimage = $plat->getLinkimage();
    }
}
?>