<?php

namespace App\Entity;

use App\Repository\ArticleContentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleContentRepository::class)]
class ArticleContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $contentType = null;

    #[ORM\Column]
    private ?int $contentOrder = null;

    #[ORM\Column(length: 20)]
    private ?string $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): static
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentOrder(): ?int
    {
        return $this->contentOrder;
    }

    public function setContentOrder(int $contentOrder): static
    {
        $this->contentOrder = $contentOrder;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }
}
