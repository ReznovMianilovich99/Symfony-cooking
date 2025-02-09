<?php

namespace App\DTO;

use App\Entity\Commande;
use App\Entity\Plat;

class CommandeDTO
{
    public ?int $id = null;
    public ?array $iduser = null; // Simplified user data
    public ?array $listplat = null; // Simplified list of plats
    public ?string $dateheurecommande = null;
    public ?string $totaleprice = null;
    public ?bool $paiementcheck = null;
    public ?bool $isready = null;
    public ?bool $issend = null;

    public function __construct(Commande $commande)
    {
        // Basic properties
        $this->id = $commande->getId();
        $this->dateheurecommande = $commande->getDateheurecommande(); // Format datetime
        $this->totaleprice = $commande->getTotaleprice();
        $this->paiementcheck = $commande->isPaiementcheck();
        $this->isready = $commande->isready();
        $this->issend = $commande->issend();

        // Simplify the User data
        $user = $commande->getIduser();
        if ($user !== null) {
            $this->iduser = [
                'id' => $user->getId(),
                'email' => $user->getEmail() // Example property
            ];
        }

        // Simplify the list of Plats
        // $this->listplat = [];
        // foreach ($commande->getListplat() as $plat) 
        // {
        //     $this->listplat[] = [
        //         'id' => $plat->getId(),
        //         'nom' => $plat->getNom(), // Example property
        //         'prix' => $plat->getPrix() // Example property
        //     ];
        // }

        $this->listplat = array_map(function (Plat $plat) 
        {
            return [
                'id' => $plat->getId(),
                'nom' => $plat->getNom(), // Example property
                'prix' => $plat->getPrix() // Example property
            ];
        }, $commande->getListplat()->toArray());
    }
}
?>