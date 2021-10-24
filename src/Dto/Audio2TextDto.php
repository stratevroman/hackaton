<?php
declare(strict_types=1);


namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

class Audio2TextDto
{
    /**
     * @Serializer\SerializedName("Text")
     */
    private string $text;
    /**
     * @Serializer\SerializedName("Text_parts_approxim")
     * @Serializer\Type("array<string>")
     */
    private array $textParts;
    /**
     * @Serializer\SerializedName("Part_coords")
     * @Serializer\Type("array<array<int>>")
     */
    private array $textPartCoords;
    /**
     * @Serializer\SerializedName("Full_audio_length")
     */
    private int $audioLength;

    /**
     * @Serializer\SerializedName("Low_quality")
     * @Serializer\Type("array<array<string>>")
     */
    private array $badWords;

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string[]
     */
    public function getTextParts(): array
    {
        return $this->textParts;
    }

    public function getTextPartCoords(): array
    {
        return $this->textPartCoords;
    }

    public function getAudioLength(): int
    {
        return $this->audioLength;
    }

    /**
     * @return string[][]
     */
    public function getBadWords(): array
    {
        return $this->badWords;
    }
}
