<?php

namespace App\Entity;

use DateTimeInterface;
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
     * @ORM\Column(type="string", length=5)
     */
    private string $temperature_range = '';

    /**
     * @ORM\Column(type="string", length=5)
     */
    private string $humidity_range = '';

    /**
     * @ORM\Column(type="string", length=17)
     */
    private string $lighting_schedule = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address = '';

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $update_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_time;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $auth = '';

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $url = '';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTemperatureRange(): string
    {
        return $this->temperature_range;
    }

    /**
     * @param string $temperature_range
     */
    public function setTemperatureRange(string $temperature_range): void
    {
        $this->temperature_range = $temperature_range;
    }

    /**
     * @return string
     */
    public function getHumidityRange(): string
    {
        return $this->humidity_range;
    }

    /**
     * @param string $humidity_range
     */
    public function setHumidityRange(string $humidity_range): void
    {
        $this->humidity_range = $humidity_range;
    }

    /**
     * @return string
     */
    public function getLightingSchedule(): string
    {
        return $this->lighting_schedule;
    }

    /**
     * @param string $lighting_schedule
     */
    public function setLightingSchedule(string $lighting_schedule): void
    {
        $this->lighting_schedule = $lighting_schedule;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdateTime(): ?DateTimeInterface
    {
        return $this->update_time;
    }

    /**
     * @param DateTimeInterface|null $update_time
     */
    public function setUpdateTime(?DateTimeInterface $update_time): void
    {
        $this->update_time = $update_time;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedTime(): ?DateTimeInterface
    {
        return $this->created_time;
    }

    /**
     * @param DateTimeInterface|null $created_time
     */
    public function setCreatedTime(?DateTimeInterface $created_time): void
    {
        $this->created_time = $created_time;
    }

    /**
     * @return string
     */
    public function getAuth(): string
    {
        return $this->auth;
    }

    /**
     * @param string $auth
     */
    public function setAuth(string $auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

}
