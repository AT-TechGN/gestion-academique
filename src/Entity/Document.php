<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $fichier = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    #[ORM\Column]
    private ?\DateTime $dateUpload = null;

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
    }

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): static
    {
        $this->fichier = $fichier;
        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;
        return $this;
    }

    public function getDateUpload(): ?\DateTime
    {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTime $dateUpload): static
    {
        $this->dateUpload = $dateUpload;
        return $this;
    }
}