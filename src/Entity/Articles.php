<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $title = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    private ?Admin $admin = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleContent::class)]
    private Collection $content;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleLog::class)]
    private Collection $log;

    public function __construct()
    {
        $this->content = new ArrayCollection();
        $this->log = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, ArticleContent>
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(ArticleContent $content): static
    {
        if (!$this->content->contains($content)) {
            $this->content->add($content);
            $content->setArticle($this);
        }

        return $this;
    }

    public function removeContent(ArticleContent $content): static
    {
        if ($this->content->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getArticle() === $this) {
                $content->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArticleLog>
     */
    public function getLog(): Collection
    {
        return $this->log;
    }

    public function addLog(ArticleLog $log): static
    {
        if (!$this->log->contains($log)) {
            $this->log->add($log);
            $log->setArticle($this);
        }

        return $this;
    }

    public function removeLog(ArticleLog $log): static
    {
        if ($this->log->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getArticle() === $this) {
                $log->setArticle(null);
            }
        }

        return $this;
    }
}
