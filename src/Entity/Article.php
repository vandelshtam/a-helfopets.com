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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $comment_foto;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    private $article;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $comment_auxiliary_one;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $preview;

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
}
