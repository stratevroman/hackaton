<?php
declare(strict_types=1);


namespace App\Dto;

class AudioDocumentResponseDto
{
    private string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
