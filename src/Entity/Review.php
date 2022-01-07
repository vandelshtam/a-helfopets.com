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
     * @ORM\OneToMany(targetEntity=Fotoreview::class, mappedBy="review")
     */
    private $fotoreview;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $answer_id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    public function __construct()
    {
        $this->fotoreview = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAnswerId(): ?int
    {
        return $this->answer_id;
    }

    public function setAnswerId(?int $answer_id): self
    {
        $this->answer_id = $answer_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
