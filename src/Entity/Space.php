<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpaceRepository")
 */
class Space
{
    use TimestampableEntity;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $technical_details;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $logistic_details;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact_information;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $display;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="space")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getTechnicalDetails(): ?string
    {
        return $this->technical_details;
    }

    public function setTechnicalDetails(?string $technical_details): self
    {
        $this->technical_details = $technical_details;

        return $this;
    }

    public function getLogisticDetails(): ?string
    {
        return $this->logistic_details;
    }

    public function setLogisticDetails(?string $logistic_details): self
    {
        $this->logistic_details = $logistic_details;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(?string $contact_name): self
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    public function setContactEmail(?string $contact_email): self
    {
        $this->contact_email = $contact_email;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): self
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    public function getContactInformation(): ?string
    {
        return $this->contact_information;
    }

    public function setContactInformation(?string $contact_information): self
    {
        $this->contact_information = $contact_information;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(?string $display): self
    {
        $this->display = $display;

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
            $event->setSpace($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getSpace() === $this) {
                $event->setSpace(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?: "Space";
    }
}
