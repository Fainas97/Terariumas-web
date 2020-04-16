<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TerrariumDataRepository")
 */
class TerrariumData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private ?string $temperature;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private ?string $humidity;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $light;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $time;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $terrarium_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidity(): ?string
    {
        return $this->humidity;
    }

    public function setHumidity(string $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getLight(): ?bool
    {
        return $this->light;
    }

    public function setLight(bool $light): self
    {
        $this->light = $light;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTerrariumId(): ?int
    {
        return $this->terrarium_id;
    }

    public function setTerrariumId(int $terrarium_id): self
    {
        $this->terrarium_id = $terrarium_id;

        return $this;
    }
}
