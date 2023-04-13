<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'clients')]
#[UniqueEntity(fields: ['courriel'], message: 'Il y a déja un compte qui possède cette adresse courriel!')]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idClient = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "Votre adresse courriel: {{ value }} est invalide")]
    private ?string $courriel = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, minMessage: "Votre nom doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "Votre nom doit contenir {{ limit }} caractères maximum")]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, minMessage: "Votre prenom doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "Votre prenom doit contenir {{ limit }} caractères maximum")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 101)]
    #[Assert\Length(min: 5, minMessage: "Votre adresse doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 100, maxMessage: "Votre adresse doit contenir {{ limit }} caractères maximum")]
    private ?string $adresse = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, minMessage: "La ville doit contenir {{ limit }} caractères minimum")]
    #[Assert\Length(max: 30, maxMessage: "La ville doit contenir {{ limit }} caractères maximum")]
    private ?string $ville = null;

    #[ORM\Column(length: 10)]
    #[Assert\Regex(pattern: "/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i", message: "Le code postal de ne respecte pas le format Canadien")]
    private ?string $codePostal = null;

    #[ORM\Column(length: 2)]
    private ?string $province = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Regex(pattern: "/^[0-9]{10}$/", message: "Votre téléphone doit contenir 10 chiffres")]
    private ?string $telephone = null;

    #[ORM\Column]
    private array $roles = [];

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): self
    {
        $this->courriel = $courriel;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->courriel;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
