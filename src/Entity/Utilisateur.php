<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('login')]
#[UniqueEntity('adresseEmail')]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Delete(),
        new Patch()
    ],
    normalizationContext: ["groups" => ["utilisateur:read"]]
)]
class Utilisateur implements UserInterface/*, PasswordAuthenticatedUserInterface*/
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['utilisateur:read', 'publication:read'])]
    private ?int $id = null;
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(min:4, max:20, minMessage: "Il faut au moins 4 caractères", maxMessage: "Il faut au plus 20 caractères")]
    #[Groups(['utilisateur:read', 'publication:read'])]
    private ?string $login = null;

    #[ORM\Column]
    #[Groups(['utilisateur:read', 'publication:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
//    #[ORM\Column]
//    private ?string $password = null;

    #[ORM\Column(length: 255, unique:true)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email(message:"l'adresse mail n'est pas valide")]
    #[Groups(['utilisateur:read', 'publication:read'])]
    private ?string $adresseEmail = null;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Publication::class, orphanRemoval: true)]
    private Collection $publications;
    #[ORM\Column(options: ["default" => false])]
    #[ApiProperty(readable : true, writable: false)]
    #[Groups(['utilisateur:read', 'publication:read'])]
    private ?bool $premium = false;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

//    /**
//     * @see PasswordAuthenticatedUserInterface
//     */
//    public function getPassword(): string
//    {
//        return $this->password;
//    }
//
//    public function setPassword(string $password): static
//    {
//        $this->password = $password;
//
//        return $this;
//    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(string $adresseEmail): static
    {
        $this->adresseEmail = $adresseEmail;

        return $this;
    }

    public function isPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): static
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setAuteur($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getAuteur() === $this) {
                $publication->setAuteur(null);
            }
        }

        return $this;
    }


}
