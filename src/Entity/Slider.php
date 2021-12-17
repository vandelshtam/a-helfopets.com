<?php

namespace App\Entity;

use App\Repository\SliderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SliderRepository::class)
 */
class Slider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $service_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discription_service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(?string $service_name): self
    {
        $this->service_name = $service_name;

        return $this;
    }

    public function getDiscriptionService(): ?string
    {
        return $this->discription_service;
    }

    public function setDiscriptionService(?string $discription_service): self
    {
        $this->discription_service = $discription_service;

        return $this;
    }
}
