<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VolunteerAvailabilityRepository")
 * @Gedmo\Loggable
 */
class VolunteerAvailability
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Volunteer", inversedBy="availabilities")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $volunteer;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $end;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): self
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function __toString()
    {
        if ($this->volunteer) {
            return $this->volunteer->__toString() . " Availability #" . ($this->getVolunteer()->getAvailabilities()->indexOf($this) + 1);
        } else {
            return "New Availability";
        }
    }
}
