<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Instance", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $scheduled = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Space", inversedBy="events")
     */
    private $space;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Submitter", inversedBy="events")
     */
    private $submitters;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Repetition", mappedBy="event", orphanRemoval=true, cascade={"persist"})
     */
    private $repetitions;

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

    public function setTeam(string $team): self
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

    public function getData(): ?array
    {
        return $this->data;
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

    public function __toString()
    {
        return $this->getTitle() ?: "New Event";
    }
}
