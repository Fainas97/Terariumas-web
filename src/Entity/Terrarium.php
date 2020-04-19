<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TerrariumRepository")
 */
class Terrarium
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string")
     */
    private string $settings = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address = '';

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $update_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $created_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
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

    public function getSettings(): ?string
    {
        return $this->settings;
    }

    public function setSettings(string $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->update_time;
    }

    public function setUpdateTime(\DateTimeInterface $update_time): self
    {
        $this->update_time = $update_time;

        return $this;
    }

    public function getCreatedTime(): ?\DateTimeInterface
    {
        return $this->created_time;
    }

    public function setCreatedTime(\DateTimeInterface $created_time): self
    {
        $this->created_time = $created_time;

        return $this;
    }

}
