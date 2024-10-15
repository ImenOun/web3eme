<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $nbrPage = null;

    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $picture = null;
    #[ORM\Column(type: 'boolean')]
    private bool $published; // Définit le type à bool

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'livres')]
    private ?Author $author = null; // Relation ManyToOne avec Author

    public function __construct()
    {
        // Initialiser `published` à true par défaut
        $this->published = true;
    }

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getNbrPage(): ?int
    {
        return $this->nbrPage;
    }

    public function setNbrPage(int $nbrPage): static
    {
        $this->nbrPage = $nbrPage;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    // Ajout de la relation avec `Author`
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    #[ORM\Column(length: 50)]
    private ?string $category = null;

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $publicationDate = null; 

    // Ajoutez les getters et setters ici

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}
