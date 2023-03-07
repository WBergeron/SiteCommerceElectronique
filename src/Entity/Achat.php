<?php

namespace App\Entity;

class Achat
{
    private $idProduit;
    private $quantite;
    private $prixAchat;

    public function __construct($idProduit, $quantite, $prixAchat)
    {
        $this->idProduit = $idProduit;
        $this->quantite = $quantite;
        $this->prixAchat = $prixAchat;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }
}
