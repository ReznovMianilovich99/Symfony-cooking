<?php
namespace App\DTO;

use App\Entity\Ingredient;

class IngredientDTO
{
    public ?int $id = null;
    public ?string $nom = null;
    public ?int $stock = null;
    public ?string $imagelink = null;

    public function __construct(Ingredient $ingredient)
    {
        // Initialisation des propriétés de base
        $this->id = $ingredient->getId();
        $this->nom = $ingredient->getNom();
        $this->stock = $ingredient->getStock();
        $this->imagelink = $ingredient->getImagelink();
    }
}
?>