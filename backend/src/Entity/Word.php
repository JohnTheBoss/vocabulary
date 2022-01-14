<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WordRepository::class)
 */
class Word
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
    private $knownLanguage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $foreignLanguage;

    /**
     * @ORM\ManyToOne(targetEntity=Dictionary::class, inversedBy="words")
     */
    private $dictionary;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDictionary(): ?Dictionary
    {
        return $this->dictionary;
    }

    public function setDictionary(?Dictionary $dictionary): self
    {
        $this->dictionary = $dictionary;

        return $this;
    }
}
