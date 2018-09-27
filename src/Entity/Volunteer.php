<?php

namespace App\Entity;

use App\Annotations as App;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $subscription = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $updates = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $joined = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $preparation = false;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Gedmo\Versioned
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     * @Gedmo\Versioned
     */
    private $shirtSize;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Gedmo\Versioned
     */
    private $original_data = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VolunteerAvailability", mappedBy="volunteer", orphanRemoval=true, cascade={"persist"})
     */
    private $availabilities;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $uniqueId;

    public function __construct()
    {
        $this->availabilities = new ArrayCollection();
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

    public function getFatherName(): ?string
    {
        return $this->father_name;
    }

    public function setFatherName(?string $father_name): self
    {
        $this->father_name = $father_name;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getName() . ' ' . $this->getSurname();
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

    public function getShirtSize(): ?string
    {
        return $this->shirtSize;
    }

    public function setShirtSize(?string $shirtSize): self
    {
        $this->shirtSize = $shirtSize;

        return $this;
    }

    public function getOriginalData(): ?array
    {
        return $this->original_data;
    }

    public function setOriginalData(?array $original_data): self
    {
        $this->original_data = $original_data;

        return $this;
    }

    /**
     * @return Collection|VolunteerAvailability[]
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(VolunteerAvailability $availability): self
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities[] = $availability;
            $availability->setVolunteer($this);
        }

        return $this;
    }

    public function removeAvailability(VolunteerAvailability $availability): self
    {
        if ($this->availabilities->contains($availability)) {
            $this->availabilities->removeElement($availability);
            // set the owning side to null (unless already changed)
            if ($availability->getVolunteer() === $this) {
                $availability->setVolunteer(null);
            }
        }

        return $this;
    }

    public function removeAvailabilities(): self
    {
        $this->availabilities->clear();

        return $this;
    }

    public function getUniqueId(): ?int
    {
        return $this->uniqueId;
    }

    public function setUniqueId(?int $uniqueId): self
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }
}
