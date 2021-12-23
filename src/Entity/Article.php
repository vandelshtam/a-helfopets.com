<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=100, nullable=true)
     */
    private $comment_foto;

    /**
     * @ORM\Column(type="text", length=5000, nullable=true)
     */
    private $article;

    /**
     * @ORM\Column(type="text", length=100, nullable=true)
     */
    private $comment_auxiliary_one;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar_article;

    /**
     * @ORM\Column(type="text", length=100, nullable=true)
     */
    private $foto1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $foto2;

    /**
     * @ORM\Column(type="string", length=2550, nullable=true)
     */
    private $comment_foto2;

    /**
     * @ORM\Column(type="text", length=2550, nullable=true)
     */
    private $paragraph1;

    /**
     * @ORM\Column(type="text", length=2550, nullable=true)
     */
    private $paragraph2;

    /**
     * @ORM\Column(type="text", length=2550, nullable=true)
     */
    private $paragraph3;

    /**
     * @ORM\Column(type="text", length=2550, nullable=true)
     */
    private $paragraph4;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCommentFoto(): ?string
    {
        return $this->comment_foto;
    }

    public function setCommentFoto(?string $comment_foto): self
    {
        $this->comment_foto = $comment_foto;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getCommentAuxiliaryOne(): ?string
    {
        return $this->comment_auxiliary_one;
    }

    public function setCommentAuxiliaryOne(?string $comment_auxiliary_one): self
    {
        $this->comment_auxiliary_one = $comment_auxiliary_one;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getAvatarArticle(): ?string
    {
        return $this->avatar_article;
    }

    public function setAvatarArticle(?string $avatar_article): self
    {
        $this->avatar_article = $avatar_article;

        return $this;
    }

    public function getFoto1(): ?string
    {
        return $this->foto1;
    }

    public function setFoto1(?string $foto1): self
    {
        $this->foto1 = $foto1;

        return $this;
    }

    public function getFoto2(): ?string
    {
        return $this->foto2;
    }

    public function setFoto2(?string $foto2): self
    {
        $this->foto2 = $foto2;

        return $this;
    }

    public function getCommentFoto2(): ?string
    {
        return $this->comment_foto2;
    }

    public function setCommentFoto2(?string $comment_foto2): self
    {
        $this->comment_foto2 = $comment_foto2;

        return $this;
    }

    public function getParagraph1(): ?string
    {
        return $this->paragraph1;
    }

    public function setParagraph1(?string $paragraph1): self
    {
        $this->paragraph1 = $paragraph1;

        return $this;
    }

    public function getParagraph2(): ?string
    {
        return $this->paragraph2;
    }

    public function setParagraph2(?string $paragraph2): self
    {
        $this->paragraph2 = $paragraph2;

        return $this;
    }

    public function getParagraph3(): ?string
    {
        return $this->paragraph3;
    }

    public function setParagraph3(?string $paragraph3): self
    {
        $this->paragraph3 = $paragraph3;

        return $this;
    }

    public function getParagraph4(): ?string
    {
        return $this->paragraph4;
    }

    public function setParagraph4(?string $paragraph4): self
    {
        $this->paragraph4 = $paragraph4;

        return $this;
    }

    public function getCreated_at(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }
}
