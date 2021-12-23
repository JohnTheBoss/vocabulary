<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\Column(type="integer")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=DictionaryEnrol::class, mappedBy="user")
     */
    private $dictionaryEnrols;

    public function __construct()
    {
        $this->dictionaryEnrols = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|DictionaryEnrol[]
     */
    public function getDictionaryEnrols(): Collection
    {
        return $this->dictionaryEnrols;
    }

    public function addDictionaryEnrol(DictionaryEnrol $dictionaryEnrol): self
    {
        if (!$this->dictionaryEnrols->contains($dictionaryEnrol)) {
            $this->dictionaryEnrols[] = $dictionaryEnrol;
            $dictionaryEnrol->setUser($this);
        }

        return $this;
    }

    public function removeDictionaryEnrol(DictionaryEnrol $dictionaryEnrol): self
    {
        if ($this->dictionaryEnrols->removeElement($dictionaryEnrol)) {
            // set the owning side to null (unless already changed)
            if ($dictionaryEnrol->getUser() === $this) {
                $dictionaryEnrol->setUser(null);
            }
        }

        return $this;
    }
}
