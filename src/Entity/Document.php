<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document3;

    /**
     * @ORM\ManyToOne(targetEntity=Achievements::class, inversedBy="document")
     */
    private $achievements;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocument1(): ?string
    {
        return $this->document1;
    }

    public function setDocument1(?string $document1): self
    {
        $this->document1 = $document1;

        return $this;
    }

    public function getDocument2(): ?string
    {
        return $this->document2;
    }

    public function setDocument2(?string $document2): self
    {
        $this->document2 = $document2;

        return $this;
    }

    public function getDocument3(): ?string
    {
        return $this->document3;
    }

    public function setDocument3(?string $document3): self
    {
        $this->document3 = $document3;

        return $this;
    }

    public function getAchievements(): ?Achievements
    {
        return $this->achievements;
    }

    public function setAchievements(?Achievements $achievements): self
    {
        $this->achievements = $achievements;

        return $this;
    }
}
