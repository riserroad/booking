<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeMessage;

    public function __construct()
    {
        $this->timeMessage = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getTimeMessage(): ?\DateTimeInterface
    {
        return $this->timeMessage;
    }

    public function setTimeMessage(\DateTimeInterface $timeMessage): self
    {
        $this->timeMessage = $timeMessage;

        return $this;
    }
}
