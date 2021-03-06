<?php

namespace App\Entity;

use App\Repository\DictionaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DictionaryRepository::class)
 */
class Dictionary
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $knownLanguage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $foreignLanguage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dictionaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Word::class, mappedBy="dictionary")
     */
    private $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
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

    public function getKnownLanguage(): ?string
    {
        return $this->knownLanguage;
    }

    public function setKnownLanguage(string $knownLanguage): self
    {
        $this->knownLanguage = $knownLanguage;

        return $this;
    }

    public function getForeignLanguage(): ?string
    {
        return $this->foreignLanguage;
    }

    public function setForeignLanguage(string $foreignLanguage): self
    {
        $this->foreignLanguage = $foreignLanguage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->setDictionary($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->removeElement($word)) {
            // set the owning side to null (unless already changed)
            if ($word->getDictionary() === $this) {
                $word->setDictionary(null);
            }
        }

        return $this;
    }
}
