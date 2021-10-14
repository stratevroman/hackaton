<?php
declare(strict_types=1);


namespace App\Dto;

class AudioRequestDto
{
    private string $audioFilePath;
    private string $audioFileName;
    private bool $isRussianLanguage;
    private bool $isLast;

    public function __construct(
        string $audioFilePath,
        string $audioFileName,
        bool $isRussianLanguage,
        bool $isLast
    ) {
        $this->audioFilePath = $audioFilePath;
        $this->audioFileName = $audioFileName;
        $this->isRussianLanguage = $isRussianLanguage;
        $this->isLast = $isLast;
    }

    public function getAudioFilePath(): string
    {
        return $this->audioFilePath;
    }

    public function getAudioFileName(): string
    {
        return $this->audioFileName;
    }

    public function isRussianLanguage(): bool
    {
        return $this->isRussianLanguage;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }
}
