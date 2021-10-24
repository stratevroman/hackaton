<?php
declare(strict_types=1);


namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

class Audio2TextAsyncResponseDto
{
    /**
     * @Serializer\SerializedName("Result")
     */
    private ?string $result = null;

    /**
     * @Serializer\SerializedName("Error")
     */
    private ?string $error = null;

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}
