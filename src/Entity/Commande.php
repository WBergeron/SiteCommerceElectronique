<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'dateCommande', nullable: false)]
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

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Achat::class, orphanRemoval: true, cascade: ["persist"])]
    #[ORM\JoinColumn(name: 'idAchat', nullable: false)]
    private Collection $achat;

    public function __construct(Client $client, Panier $panier, string $StripeIntent)
    {
        // Devrais être set avec le datetime d'origine de Londre
        // et peut-être set le format en base de données
        $this->dateCommande = new DateTime();
        $this->dateLivraison = null;
        $this->tauxTPS = Constantes::$TPS;
        $this->tauxTVQ = Constantes::$TVQ;
        $this->fraisLivraison = Constantes::$FRAIS_LIVRAISON;
        $this->etat = "preparation";
        $this->stripeIntent = $StripeIntent;
        $this->client = $client;

        $this->achat = new ArrayCollection();

        foreach ($panier->getPanier() as $achat) {
            $this->achat->add($achat);
            $achat->setCommande($this);
        }
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function getTauxTPS(): ?float
    {
        return $this->tauxTPS;
    }

    public function getTauxTVQ(): ?float
    {
        return $this->tauxTVQ;
    }

    public function getFraisLivraison(): ?float
    {
        return $this->fraisLivraison;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
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

    public function getAchat(): Collection
    {
        return $this->achat;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achat->contains($achat)) {
            $this->achat->add($achat);
            $achat->setCommande($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achat->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getCommande() === $this) {
                $achat->setCommande(null);
            }
        }

        return $this;
    }
}
