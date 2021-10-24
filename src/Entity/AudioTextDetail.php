<?php

namespace App\Entity;

use App\Repository\AudioTextDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudioTextDetailRepository::class)
 */
class AudioTextDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $text;

    /**
     * @ORM\Column(type="integer")
     */
    private int $offsetAtFromStartText;

    /**
     * @ORM\Column(type="integer")
     */
    private int $numberOfCharacters;

    /**
     * @ORM\Column(type="integer")
     */
    private int $startAt;

    /**
     * @ORM\Column(type="integer")
     */
    private int $endAt;

    /**
     * @ORM\OneToMany(targetEntity=AudioTextDetailBadWord::class, mappedBy="audioTextDetail")
     */
    private ArrayCollection $badWords;

    /**
     * @ORM\ManyToOne(targetEntity=AudioText::class, inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private AudioText $audioText;

    public function __construct()
    {
        $this->badWords = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getOffsetAtFromStartText(): int
    {
        return $this->offsetAtFromStartText;
    }

    public function setOffsetAtFromStartText(int $offsetAtFromStartText): self
    {
        $this->offsetAtFromStartText = $offsetAtFromStartText;

        return $this;
    }

    public function getNumberOfCharacters(): int
    {
        return $this->numberOfCharacters;
    }

    public function setNumberOfCharacters(int $numberOfCharacters): self
    {
        $this->numberOfCharacters = $numberOfCharacters;

        return $this;
    }

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function setStartAt(int $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): int
    {
        return $this->endAt;
    }

    public function setEndAt(int $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection|AudioTextDetailBadWord[]
     */
    public function getBadWords(): Collection
    {
        return $this->badWords;
    }

    public function addBadWord(AudioTextDetailBadWord $badWord): self
    {
        if (!$this->badWords->contains($badWord)) {
            $this->badWords[] = $badWord;
            $badWord->setAudioTextDetail($this);
        }

        return $this;
    }

    public function removeBadWord(AudioTextDetailBadWord $badWord): self
    {
        if ($this->badWords->removeElement($badWord)) {
            // set the owning side to null (unless already changed)
            if ($badWord->getAudioTextDetail() === $this) {
                $badWord->setAudioTextDetail(null);
            }
        }

        return $this;
    }

    public function getAudioText(): ?AudioText
    {
        return $this->audioText;
    }

    public function setAudioText(?AudioText $audioText): self
    {
        $this->audioText = $audioText;

        return $this;
    }
}
