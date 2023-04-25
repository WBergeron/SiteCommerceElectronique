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

    #[ORM\Column(name: 'quantite', nullable: false)]
    private ?int $quantite = null;

    #[ORM\Column(name: 'prixAchat', nullable: false)]
    private ?float $prixAchat = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'idProduit', nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'achat')]
    #[ORM\JoinColumn(name: 'idCommande', referencedColumnName: 'idCommande', nullable: false)]
    private ?Commande $commande = null;

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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
