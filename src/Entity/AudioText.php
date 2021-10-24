<?php

namespace App\Entity;

use App\Repository\AudioTextRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=AudioTextRepository::class)
 */
class AudioText
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $body;

    /**
     * @ORM\OneToOne(targetEntity=Audio::class, inversedBy="audioText", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private Audio $audio;

    /**
     * @ORM\OneToMany(targetEntity=AudioTextDetail::class, mappedBy="audioText", orphanRemoval=true)
     */
    private ArrayCollection $details;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getAudio(): Audio
    {
        return $this->audio;
    }

    public function setAudio(Audio $audio): self
    {
        $this->audio = $audio;

        return $this;
    }

    public function setId(int $id): AudioText
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|AudioTextDetail[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addAudioTextDetail(AudioTextDetail $audioTextDetail): self
    {
        if (!$this->details->contains($audioTextDetail)) {
            $this->details[] = $audioTextDetail;
            $audioTextDetail->setAudioText($this);
        }

        return $this;
    }

    public function removeAudioTextDetail(AudioTextDetail $audioTextDetail): self
    {
        if ($this->details->removeElement($audioTextDetail)) {
            // set the owning side to null (unless already changed)
            if ($audioTextDetail->getAudioText() === $this) {
                $audioTextDetail->setAudioText(null);
            }
        }

        return $this;
    }
}
