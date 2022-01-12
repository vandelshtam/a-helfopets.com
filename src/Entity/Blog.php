<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $text2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Fotoblog::class, mappedBy="blog")
     */
    private $fotoblog;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linltitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleslider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionslider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkslider;

    /**
     * @ORM\OneToMany(targetEntity=Ratingblog::class, mappedBy="blog")
     */
    private $ratingblog;

    public function __construct()
    {
        $this->fotoblog = new ArrayCollection();
        $this->ratingblog = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(?string $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
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
     * @return Collection|Fotoblog[]
     */
    public function getFotoblog(): Collection
    {
        return $this->fotoblog;
    }

    public function addFotoblog(Fotoblog $fotoblog): self
    {
        if (!$this->fotoblog->contains($fotoblog)) {
            $this->fotoblog[] = $fotoblog;
            $fotoblog->setBlog($this);
        }

        return $this;
    }

    public function removeFotoblog(Fotoblog $fotoblog): self
    {
        if ($this->fotoblog->removeElement($fotoblog)) {
            // set the owning side to null (unless already changed)
            if ($fotoblog->getBlog() === $this) {
                $fotoblog->setBlog(null);
            }
        }

        return $this;
    }
    public function getLinltitle(): ?string
    {
        return $this->linltitle;
    }

    public function setLinltitle(?string $linltitle): self
    {
        $this->linltitle = $linltitle;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTitleslider(): ?string
    {
        return $this->titleslider;
    }

    public function setTitleslider(?string $titleslider): self
    {
        $this->titleslider = $titleslider;

        return $this;
    }

    public function getDescriptionslider(): ?string
    {
        return $this->descriptionslider;
    }

    public function setDescriptionslider(?string $descriptionslider): self
    {
        $this->descriptionslider = $descriptionslider;

        return $this;
    }

    public function getLinkslider(): ?string
    {
        return $this->linkslider;
    }

    public function setLinkslider(?string $linkslider): self
    {
        $this->linkslider = $linkslider;

        return $this;
    }
    public function __toString()
    {
      return $this->getTitle();
    }

    /**
     * @return Collection|Ratingblog[]
     */
    public function getRatingblog(): Collection
    {
        return $this->ratingblog;
    }

    public function addRatingblog(Ratingblog $ratingblog): self
    {
        if (!$this->ratingblog->contains($ratingblog)) {
            $this->ratingblog[] = $ratingblog;
            $ratingblog->setBlog($this);
        }

        return $this;
    }

    public function removeRatingblog(Ratingblog $ratingblog): self
    {
        if ($this->ratingblog->removeElement($ratingblog)) {
            // set the owning side to null (unless already changed)
            if ($ratingblog->getBlog() === $this) {
                $ratingblog->setBlog(null);
            }
        }

        return $this;
    }
}
