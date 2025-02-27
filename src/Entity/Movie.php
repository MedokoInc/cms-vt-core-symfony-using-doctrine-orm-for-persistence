<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $release = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Quote::class, orphanRemoval: true)]
    private Collection $quotes;

    #[ORM\Column(nullable: true)]
    private ?int $cat_count = null;

    public function __construct()
    {
        $this->quotes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRelease(): ?\DateTimeInterface
    {
        return $this->release;
    }

    public function setRelease(?\DateTimeInterface $release): self
    {
        $this->release = $release;

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): self
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
            $quote->setMovie($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): self
    {
        if ($this->quotes->removeElement($quote)) {
            // set the owning side to null (unless already changed)
            if ($quote->getMovie() === $this) {
                $quote->setMovie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' (' . $this->release->format('d.m.Y') . ')';
    }

    public function getCatCount(): ?int
    {
        return $this->cat_count;
    }

    public function setCatCount(?int $cat_count): self
    {
        $this->cat_count = $cat_count;

        return $this;
    }

    #[Assert\IsTrue(message: 'Name must be even number of letters and each letter must be repeated twice')]
    private function isValidName(): bool
    {
        $res = strtolower($this->name);
        if(strlen($res) % 2 !== 0) {
            return false;
        }
        $res = str_split($res);
        $res = array_count_values($res);
        foreach($res as $letter => $count) {
            if($count !== 2) {
                return false;
            }
        }
        return true;
    }
}
