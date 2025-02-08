<?php

namespace App\DTO;
use App\Entity\Plat;
use App\Entity\Historique;

class HistoDTO
{
    public $id;
    public $Dateheurehisto;
    public $idingredient;

    public function __construct(Historique $rct)
    {
        $this->id = $rct->getId();
        $this->Dateheurehisto = $rct->getDateheurehisto();
        $plat = $rct->getIdplat();
        $this->idplat = 
        [
            'idplat' => $plat->getId(),
            'nomplat' => $plat->getNom()
        ];
    }
}
?>