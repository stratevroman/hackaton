<?php
declare(strict_types=1);


namespace App\Dto;

class AudioRequestDto
{
    private string $audioFilePath;
    private string $audioFileName;
    private string $language;
    private bool $isLast;

    public function __construct(
        string $audioFilePath,
        string $audioFileName,
        string $language,
        bool $isLast
    ) {
        $this->audioFilePath = $audioFilePath;
        $this->audioFileName = $audioFileName;
        $this->language = $language;
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

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }
}
