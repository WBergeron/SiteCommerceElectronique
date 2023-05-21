<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function PHPUnit\Framework\stringContains;

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

    #[ORM\ManyToOne(inversedBy: 'inventaire', cascade: ["persist"])]
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
        $this->etat = "En Preparation";
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

    public function getSousTotal()
    {
        $sousTotal = 0;
        foreach ($this->achat as $item) {
            $sousTotal += $item->getPrixAchat() * $item->getQuantite();
        }
        return $sousTotal;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->achat as $item) {
            $sommaire = ($item->getPrixAchat() * $item->getQuantite());
            $tps = $sommaire * $this->tauxTPS;
            $tvq = $sommaire * $this->tauxTVQ;
            $total += $sommaire + $tps + $tvq + $this->fraisLivraison;
        }
        return $total;
    }

    public function getNbItems()
    {
        $quantiteItem = 0;
        foreach ($this->achat as $item) {
            $quantiteItem += $item->getQuantite();
        }
        return $quantiteItem;
    }

    public function getDateLivraisonAffichage()
    {
        if ($this->dateLivraison) {
            return $this->dateLivraison->format('Y-m-d H:i:s');
        } else {
            return "À venir";
        }
    }

    public function estAMoi(Client $client)
    {
        if ($this->client == $client) {
            return true;
        } else {
            return false;
        }
    }
}
