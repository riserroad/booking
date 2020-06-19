<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEvent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimitRegristation;

    /**
     * @ORM\Column(type="integer")
     */
    private $limitPartcipant;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="event", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=RegistrationEvent::class, mappedBy="event", orphanRemoval=true)
     */
    private $registrationEvents;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->registrationEvents = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        return $this->dateLimitRegristration;
    }

    public function setDateLimitSubcriber(\DateTimeInterface $dateLimitSubcriber): self
    {
        $this->dateLimitSubcriber = $dateLimitSubcriber;

        return $this;
    }

    public function getLimitPartcipant(): ?int
    {
        return $this->limitPartcipant;
    }

    public function setLimitPartcipant(int $limitPartcipant): self
    {
        $this->limitPartcipant = $limitPartcipant;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setEvent($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getEvent() === $this) {
                $message->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RegistrationEvent[]
     */
    public function getRegistrationEvents(): Collection
    {
        return $this->registrationEvents;
    }

    public function addRegistrationEvent(RegistrationEvent $registrationEvent): self
    {
        if (!$this->registrationEvents->contains($registrationEvent)) {
            $this->registrationEvents[] = $registrationEvent;
            $registrationEvent->setEvent($this);
        }

        return $this;
    }

    public function removeRegistrationEvent(RegistrationEvent $registrationEvent): self
    {
        if ($this->registrationEvents->contains($registrationEvent)) {
            $this->registrationEvents->removeElement($registrationEvent);
            // set the owning side to null (unless already changed)
            if ($registrationEvent->getEvent() === $this) {
                $registrationEvent->setEvent(null);
            }
        }

        return $this;
    }
}
