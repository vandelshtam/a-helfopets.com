<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Fotorev::class, inversedBy="name")
     */
    private $fotorev;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Fotoreview::class, mappedBy="review")
     */
    private $fotoreview;

    public function __construct()
    {
        $this->fotoreview = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFotorev(): ?Fotorev
    {
        return $this->fotorev;
    }

    public function setFotorev(?Fotorev $fotorev): self
    {
        $this->fotorev = $fotorev;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|Fotoreview[]
     */
    public function getFotoreview(): Collection
    {
        return $this->fotoreview;
    }

    public function addFotoreview(Fotoreview $fotoreview): self
    {
        if (!$this->fotoreview->contains($fotoreview)) {
            $this->fotoreview[] = $fotoreview;
            $fotoreview->setReview($this);
        }

        return $this;
    }

    public function removeFotoreview(Fotoreview $fotoreview): self
    {
        if ($this->fotoreview->removeElement($fotoreview)) {
            // set the owning side to null (unless already changed)
            if ($fotoreview->getReview() === $this) {
                $fotoreview->setReview(null);
            }
        }

        return $this;
    }
}
