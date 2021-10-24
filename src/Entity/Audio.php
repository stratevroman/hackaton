<?php

namespace App\Entity;

use App\Repository\AudioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudioRepository::class)
 */
class Audio
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $url;

    /**
     * @ORM\OneToOne(targetEntity=AudioText::class, mappedBy="audio", cascade={"persist", "remove"})
     */
    private ?AudioText $audioText;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAudioText(): ?AudioText
    {
        return $this->audioText;
    }

    public function setAudioText(AudioText $audioText): self
    {
        // set the owning side of the relation if necessary
        if ($audioText->getAudio() !== $this) {
            $audioText->setAudio($this);
        }

        $this->audioText = $audioText;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
