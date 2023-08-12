<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\OneToOne(mappedBy: 'admin', cascade: ['persist', 'remove'])]
    private ?Users $user = null;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Articles::class)]
    private Collection $article;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: ArticleLog::class)]
    private Collection $log;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Reports::class)]
    private Collection $report;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->log = new ArrayCollection();
        $this->report = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setAdmin(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAdmin() !== $this) {
            $user->setAdmin($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Articles $article): static
    {
        if (!$this->article->contains($article)) {
            $this->article->add($article);
            $article->setAdmin($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): static
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAdmin() === $this) {
                $article->setAdmin(null);
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
            $log->setAdmin($this);
        }

        return $this;
    }

    public function removeLog(ArticleLog $log): static
    {
        if ($this->log->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getAdmin() === $this) {
                $log->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reports>
     */
    public function getReport(): Collection
    {
        return $this->report;
    }

    public function addReport(Reports $report): static
    {
        if (!$this->report->contains($report)) {
            $this->report->add($report);
            $report->setAdmin($this);
        }

        return $this;
    }

    public function removeReport(Reports $report): static
    {
        if ($this->report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getAdmin() === $this) {
                $report->setAdmin(null);
            }
        }

        return $this;
    }
}
