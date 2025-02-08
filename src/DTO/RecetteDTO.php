<?php

namespace App\DTO;
use App\Entity\Recette;
use App\Entity\Plat;
use App\Entity\Ingredient;

class RecetteDTO
{
    public $id;
    public $idplat;
    public $idingredient;

    public function __construct(Recette $rct)
    {
        $this->id = $rct->getId();
        
        $plat = $rct->getIdplat(); // Assuming this returns a single Plat object

        $this->idplat = [
            'idplat' => $plat->getId(),
            'nomplat' => $plat->getNom()
        ];

        $this->idingredient = array_map(function (Ingredient $ingredients) 
        {
            return [
                'idIngredient' => $ingredients->getId(),
                'nomIngredient' => $ingredients->getNom(),
                'stockIngredient' => $ingredients->getStock()
            ];
        }, $rct->getIdingredient()->toArray());
    }
}
?>