<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $email = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $admin = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_date = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $update_date = null;

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

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getCreatedDate(): ?DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getUpdateDate(): ?DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function getRoles(): array
    {
        return array($this->admin);
    }

    public function getSalt()
    {
        return null;
    }


    public function getUsername(): string
    {
        return $this->name;
    }

    public function eraseCredentials() {}

}
