<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Delete(),
        new GetCollection(
            uriTemplate: '/utilisateurs/{id}/publications',
            uriVariables: [
                'id' => new Link(
                    fromProperty: 'publications',
                    fromClass: Utilisateur::class
                )
            ]
        )
    ],
    normalizationContext: ["groups" => ["publication:read"]],
    order: ["datePublication" => "DESC"]
)]
#[ORM\HasLifecycleCallbacks]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['publication:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "Le message est trop court! (4 caractères minimum)",
        maxMessage: "Le message est trop long! (50 caractères maximum)"
    )]
    #[Groups(['publication:read'])]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ApiProperty(writable:false)]
    #[Groups(['publication:read'])]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[ApiProperty(required: true)]
    #[Groups(['publication:read'])]
    private ?Utilisateur $auteur = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersistDatePublication() : void {
        $this->datePublication = new \DateTime();
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }


}
