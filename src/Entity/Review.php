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
     * @ORM\OneToMany(targetEntity=Fotoreview::class, mappedBy="review",cascade={"persist"})
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

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="review")
     */
    private $answer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $banned;

    public function __construct()
    {
        $this->fotoreview = new ArrayCollection();
        $this->answer = new ArrayCollection();
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

    /**
     * @return Collection|Answer[]
     */
    public function getAnswer(): Collection
    {
        return $this->answer;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answer->contains($answer)) {
            $this->answer[] = $answer;
            $answer->setReview($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answer->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getReview() === $this) {
                $answer->setReview(null);
            }
        }

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getBanned(): ?string
    {
        return $this->banned;
    }

    public function setBanned(?string $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
}
