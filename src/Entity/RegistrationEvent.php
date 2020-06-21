<?php

namespace App\Entity;

use App\Repository\RegistrationEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationEventRepository::class)
 */
class RegistrationEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="registrationEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="registrationEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConfirmed;

    public function __construct()
    {
        $this->isConfirmed = false; 
        $this->registrationDate = new \DateTime(); 
    }

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }
}
