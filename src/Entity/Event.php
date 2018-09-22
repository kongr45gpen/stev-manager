<?php

namespace App\Entity;

use App\Annotations as App;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Gedmo\Loggable
 * @todo Return data as object that nulls out
 */
class Event
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instance", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $instance;

    /**
     * @ORM\Column(type="string", length=255)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $team = "";

    /**
     * @ORM\Column(type="smallint")
     * @Gedmo\Versioned
     */
    private $status = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Gedmo\Versioned
     */
    private $scheduled = 0;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $hidden = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space", inversedBy="events")
     * @Gedmo\Versioned
     */
    private $space;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Submitter", inversedBy="events", orphanRemoval=true, cascade={"persist"})
     */
    private $submitters;

    /**
     * @ORM\Column(type="json")
     * @todo Make searchable
     * @Gedmo\Versioned
     */
    private $data = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Repetition", mappedBy="event", orphanRemoval=true, cascade={"persist"})
     */
    private $repetitions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $short_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @App\Searchable
     * @Gedmo\Versioned
     */
    private $long_description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $uniqueId;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $originalData = [];

    public function __construct()
    {
        $this->submitters = new ArrayCollection();
        $this->repetitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(?string $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduled(): ?int
    {
        return $this->scheduled;
    }

    public function setScheduled(int $scheduled): self
    {
        $this->scheduled = $scheduled;

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

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): self
    {
        $this->space = $space;

        return $this;
    }

    /**
     * @return Collection|Submitter[]
     */
    public function getSubmitters(): Collection
    {
        return $this->submitters;
    }

    public function addSubmitter(Submitter $submitter): self
    {
        if (!$this->submitters->contains($submitter)) {
            $this->submitters[] = $submitter;
        }

        return $this;
    }

    public function removeSubmitter(Submitter $submitter): self
    {
        if ($this->submitters->contains($submitter)) {
            $this->submitters->removeElement($submitter);
        }

        return $this;
    }

    public function removeSubmitters(): self
    {
        $this->submitters->clear();

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getDataAsObject(): \stdClass
    {
        if (!$this->data) return new \stdClass();

        return json_decode(json_encode($this->data), false);
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Collection|Repetition[]
     */
    public function getRepetitions(): Collection
    {
        return $this->repetitions;
    }

    public function addRepetition(Repetition $repetition): self
    {
        if (!$this->repetitions->contains($repetition)) {
            $this->repetitions[] = $repetition;
            $repetition->setEvent($this);
        }

        return $this;
    }

    public function removeRepetition(Repetition $repetition): self
    {
        if ($this->repetitions->contains($repetition)) {
            $this->repetitions->removeElement($repetition);
            // set the owning side to null (unless already changed)
            if ($repetition->getEvent() === $this) {
                $repetition->setEvent(null);
            }
        }

        return $this;
    }

    public function removeRepetitions(): self
    {
        $this->repetitions->clear();

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle() ?: "New Event";
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->long_description;
    }

    public function setLongDescription(?string $long_description): self
    {
        $this->long_description = $long_description;

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

    public function getOriginalData(): ?array
    {
        return $this->originalData;
    }

    public function setOriginalData(?array $originalData): self
    {
        $this->originalData = $originalData;

        return $this;
    }
}
