<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepetitionRepository")
 */
class Repetition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="repetitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space")
     */
    private $space_override;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extra;

    /**
     * @ORM\Column(type="boolean")
     */
    private $separate = false;

    /**
     * Repetition constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->date->setTime(0, 0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?bool
    {
        return $this->time;
    }

    public function setTime(bool $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration): self
    {
        $this->duration = $duration;

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

    public function getSpaceOverride(): ?Space
    {
        return $this->space_override;
    }

    public function setSpaceOverride(?Space $space_override): self
    {
        $this->space_override = $space_override;

        return $this;
    }

    public function getExtra(): ?string
    {
        return $this->extra;
    }

    public function setExtra(?string $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getSeparate(): ?bool
    {
        return $this->separate;
    }

    public function setSeparate(bool $separate): self
    {
        $this->separate = $separate;

        return $this;
    }
}
