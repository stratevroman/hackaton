<?php

declare(strict_types=1);

namespace App\Dto;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Throwable;

class ErrorMessage
{
    private string $code;
    private string $message;

    /**
     * @JMS\Groups("debug")
     */
    private string $class = '';

    /**
     * @JMS\Groups("debug")
     */
    private string $trace = '';

    public function __construct(string $message, string $code = '')
    {
        $this->message = $message;
        $this->code = $code;
    }

    public static function fromThrowable(Throwable $throwable): self
    {
        $self = new self($throwable->getMessage(), (string)$throwable->getCode());
        $self->class = get_class($throwable);
        $self->trace = $throwable->getTraceAsString();

        return $self;
    }

    public static function fromViolation(ConstraintViolationInterface $violation): self
    {
        $self = new self($violation->getMessage(), (string)$violation->getCode());
        $self->class = $violation->getPropertyPath();

        return $self;
    }

    public static function create(string $string, string $code = ''): self
    {
        $self = new self($string, $code);
        $self->class = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1]['class'] ?: '';

        return $self;
    }
}
