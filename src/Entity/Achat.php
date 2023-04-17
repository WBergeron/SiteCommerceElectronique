<?php

namespace App\Entity;

class Achat
{
    private $produit;
    private $quantite;
    private $prixAchat;

    public function __construct($produit, $quantite, $prixAchat)
    {
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prixAchat = $prixAchat;
    }

    public function updateQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function quantitePlus()
    {
        $this->quantite = $this->quantite + 1;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
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
