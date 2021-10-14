<?php

declare(strict_types=1);

namespace App\Dto;

use JMS\Serializer\Annotation as JMS;
use Countable;

final class ErrorResponse implements Countable
{
    /**
     * @var ErrorMessage[]
     * @JMS\Type("array<App\Dto\ErrorMessage>")
     */
    public array $errors = [];

    public string $runtimeUUID = '';

    public function count()
    {
        return count($this->errors);
    }

    public function pushError(ErrorMessage $errorMessage): self
    {
        $this->errors[] = $errorMessage;

        return $this;
    }

    public function setRuntimeUUID(string $runtimeUUID): self
    {
        $this->runtimeUUID = $runtimeUUID;

        return $this;
    }
}
