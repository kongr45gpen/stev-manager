<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubmitterRepository")
 * @Gedmo\Loggable
 */
class Submitter
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $surname;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $faculty;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $phone_other;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $sector;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $lab;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $hidden = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="submitters")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(?string $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getFaculty(): ?string
    {
        return $this->faculty;
    }

    public function setFaculty(?string $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoneOther(): ?string
    {
        return $this->phone_other;
    }

    public function setPhoneOther(?string $phone_other): self
    {
        $this->phone_other = $phone_other;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getLab(): ?string
    {
        return $this->lab;
    }

    public function setLab(?string $lab): self
    {
        $this->lab = $lab;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addSubmitter($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeSubmitter($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() . " " . $this->getSurname();
    }

    public function describe(): string
    {
        $details = [
            $this->getName() . " " . $this->getSurname(),
            $this->getProperty(),
            $this->getFaculty(),
            $this->getSchool(),
            $this->getSector(),
            $this->getLab(),
            $this->getPhone(),
            $this->getPhoneOther()
        ];

        $details = array_filter($details, function($detail) {
            return $detail !== null && trim($detail) != '';
        });

        return implode(', ', $details);
    }

    public function describeQuickly(): string
    {
        // TODO: Improve

        $details = [
            $this->getName() . " " . $this->getSurname(),
            $this->getProperty(),
            $this->getFaculty(),
            $this->getSchool(),
            $this->getSector(),
            $this->getLab()
        ];

        $details = array_filter($details, function($detail) {
            return $detail !== null && trim($detail) != '';
        });

        return implode(', ', $details);
    }
}
