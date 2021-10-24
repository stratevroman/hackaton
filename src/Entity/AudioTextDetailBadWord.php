<?php

namespace App\Entity;

use App\Repository\AudioTextDetailBadWordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudioTextDetailBadWordRepository::class)
 */
class AudioTextDetailBadWord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $index;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $word;

    /**
     * @ORM\ManyToOne(targetEntity=AudioTextDetail::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private AudioTextDetail $audioTextDetail;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function setIndex(int $index): self
    {
        $this->index = $index;

        return $this;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getAudioTextDetail(): AudioTextDetail
    {
        return $this->audioTextDetail;
    }

    public function setAudioTextDetail(AudioTextDetail $audioTextDetail): self
    {
        $this->audioTextDetail = $audioTextDetail;

        return $this;
    }
}
