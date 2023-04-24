<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCommande')]
    private ?int $idCommande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'dateCommande')]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'dateLivraison', nullable: true)]
    private ?\DateTimeInterface $dateLivraison = null;

    #[ORM\Column(name: 'tauxTPS')]
    private ?float $tauxTPS = null;

    #[ORM\Column(name: 'tauxTVQ')]
    private ?float $tauxTVQ = null;

    #[ORM\Column(name: 'fraisLivraison')]
    private ?float $fraisLivraison = null;

    #[ORM\Column(length: 255, name: 'etat')]
    private ?string $etat = null;

    #[ORM\Column(length: 255, name: 'stripeIntent')]
    private ?string $stripeIntent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idClient', referencedColumnName: 'idClient', nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(targetEntity: Achat::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: 'idAchat', referencedColumnName: 'idAchat', nullable: false)]
    private ?Achat $achat = null;

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getTauxTPS(): ?float
    {
        return $this->tauxTPS;
    }

    public function setTauxTPS(float $tauxTPS): self
    {
        $this->tauxTPS = $tauxTPS;

        return $this;
    }

    public function getTauxTVQ(): ?float
    {
        return $this->tauxTVQ;
    }

    public function setTauxTVQ(float $tauxTVQ): self
    {
        $this->tauxTVQ = $tauxTVQ;

        return $this;
    }

    public function getFraisLivraison(): ?float
    {
        return $this->fraisLivraison;
    }

    public function setFraisLivraison(float $fraisLivraison): self
    {
        $this->fraisLivraison = $fraisLivraison;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getStripeIntent(): ?string
    {
        return $this->stripeIntent;
    }

    public function setStripeIntent(string $stripeIntent): self
    {
        $this->stripeIntent = $stripeIntent;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): self
    {
        $this->achat = $achat;

        return $this;
    }
}
