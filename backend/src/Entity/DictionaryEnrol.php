<?php

namespace App\Entity;

use App\Repository\DictionaryEnrolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DictionaryEnrolRepository::class)
 */
class DictionaryEnrol
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dictionaryEnrols")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Dictionary::class)
     */
    private $dictionary;

    public function getId(): ?int
    {
        return $this->id;
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
