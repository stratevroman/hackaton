<?php
declare(strict_types=1);


namespace App\Dto;

class AudioResponseDto
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }


    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
