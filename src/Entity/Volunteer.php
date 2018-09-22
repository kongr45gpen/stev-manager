<?php

namespace App\Entity;

use App\Annotations as App;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VolunteerRepository")
 * @Gedmo\Loggable
 */
class Volunteer
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instance", inversedBy="volunteers")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $instance;

    /**
     * @ORM\Column(type="string", length=255)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $father_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $level;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $health;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $interests;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $subscription;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $updates;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $joined;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $preparation;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Gedmo\Versioned
     */
    private $gender;

    /**
     * @ORM\Column(type="json")
     * @Gedmo\Versioned
     */
    private $availability = [];

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

    public function getFatherName(): ?string
    {
        return $this->father_name;
    }

    public function setFatherName(?string $father_name): self
    {
        $this->father_name = $father_name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getHealth(): ?string
    {
        return $this->health;
    }

    public function setHealth(?string $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->interests;
    }

    public function setInterests(?string $interests): self
    {
        $this->interests = $interests;

        return $this;
    }

    public function getSubscription(): ?bool
    {
        return $this->subscription;
    }

    public function setSubscription(bool $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getUpdates(): ?bool
    {
        return $this->updates;
    }

    public function setUpdates(bool $updates): self
    {
        $this->updates = $updates;

        return $this;
    }

    public function getJoined(): ?bool
    {
        return $this->joined;
    }

    public function setJoined(bool $joined): self
    {
        $this->joined = $joined;

        return $this;
    }

    public function getPreparation(): ?bool
    {
        return $this->preparation;
    }

    public function setPreparation(bool $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAvailability(): ?array
    {
        return $this->availability;
    }

    public function setAvailability(array $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() . " " . $this->getSurname();
    }
}
