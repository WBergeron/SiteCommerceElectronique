<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AchatRepository::class)]
#[ORM\Table(name: 'achats')]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAchat')]
    private ?int $idAchat = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'produit', referencedColumnName: 'idProduit', nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(name: 'quantite', nullable: false)]
    private ?int $quantite = null;

    #[ORM\Column(name: 'prixAchat', nullable: false)]
    private ?float $prixAchat = null;

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

    public function getIdAchat(): ?int
    {
        return $this->idAchat;
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
