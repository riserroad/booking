<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegistrationEventRepository;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\Boolean;

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
    private $dateLimitRegistration;

    /**
     * @ORM\Column(type="integer")
     */
    private $limitParticipant;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="event", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=RegistrationEvent::class, mappedBy="event", orphanRemoval=true)
     */
    private $registrationEvents;

    private $em; 

    public function __construct(EntityManager $em)
    {
        $this->messages = new ArrayCollection();
        $this->registrationEvents = new ArrayCollection();
        $this->dateEvent = new \DateTime();
        $this->dateLimitRegistration = new \DateTime(); 
        $this->limitParticipant = 10;

        $this->em = $em; 
        

    }

    public function __toString()
    {
        return $this->name; 
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
        return $this->dateLimitRegistration;
    }

    public function setDateLimitRegistration(\DateTimeInterface $dateLimitRegistration): self
    {
        $this->dateLimitRegistration = $dateLimitRegistration;

        return $this;
    }

    public function getLimitParticipant(): ?int
    {
        return $this->limitParticipant;
    }

    public function getNumberParticipant()
    {   
        $nbParticipant = count($this->getRegistrationEvents());
        dump($this->em); 
        return $nbParticipant; 
    }

    public function setLimitParticipant(int $limitParticipant): self
    {
        $this->limitParticipant = $limitParticipant;

        return $this;
    }

    public function userIsRegistered(?User $user) : bool
    {
        if (!$user)
        {
            return false; 
        }
        $registrationEvents = $this->getRegistrationEvents();
        foreach ($registrationEvents as $registrationEvent) {
            if ($registrationEvent->getUser() == $user)
            {
                return true; 
            }
        }
        return false; 
    }
    public function userIsConfirmed(?User $user)
    {
        if (!$user)
        {
            return false;
        }
        $registrationEvents = $this->getRegistrationEvents();
        foreach ($registrationEvents as $registrationEvent) {
            if ($registrationEvent->getUser() == $user && $registrationEvent->getIsConfirmed())
            {
                return true; 
            }
        }
        return false;   
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
