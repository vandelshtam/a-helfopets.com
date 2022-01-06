<?php

namespace App\Entity;

use App\Repository\FotorevRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FotorevRepository::class)
 */
class Fotorev
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
    private $foto;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="fotorev")
     */
    private $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Review $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setFotorev($this);
        }

        return $this;
    }

    public function removeName(Review $name): self
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getFotorev() === $this) {
                $name->setFotorev(null);
            }
        }

        return $this;
    }
}
