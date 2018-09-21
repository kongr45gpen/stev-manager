<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepetitionRepository")
 * @Gedmo\Loggable
 */
class Repetition
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     * @var Carbon
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $end_date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="repetitions")
     * @Gedmo\Versioned
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space")
     * @Gedmo\Versioned
     */
    private $space_override;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $extra;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
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

    public function getDate(): ?Carbon
    {
        // TODO: No, make sure original date is carbon?
        return Carbon::instance($this->date);
    }

    public function setDate(\DateTimeInterface $date): self
    {
        // TODO: Carbonise
        $this->date = Carbon::instance($date);

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

    public function getEndTime(): ?Carbon
    {
        return $this->date->copy()->addMinutes($this->duration);
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
