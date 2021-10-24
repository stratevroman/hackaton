<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Dto\ErrorMessage;
use App\Dto\RestResponse;
use App\Exception\RestException;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\SerializationContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use JMS\Serializer\Serializer;
use JMS\Serializer\Exception\Exception as SerializerExceptionInterface;

class Exception2JsonListener implements EventSubscriberInterface
{
    private Serializer $serializer;
    private string $kernelEnvironment;

    public function __construct(
        Serializer $serializer,
        string $kernelEnvironment
    ) {
        $this->serializer = $serializer;
        $this->kernelEnvironment = $kernelEnvironment;
    }

    /**
     * Serialize entity and initializes a new response object with the json content
     */
    public function onKernelView(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $response = RestResponse::new(RestResponse::HTTP_INTERNAL_SERVER_ERROR);

        switch (true) {
            case $throwable instanceof HttpExceptionInterface:
                $response->setStatusCode($throwable->getStatusCode());
                break;
            case $throwable instanceof SerializerExceptionInterface:
                $response->setStatusCode(RestResponse::HTTP_BAD_REQUEST);
                break;
            case $throwable instanceof EntityNotFoundException:
                $response->setStatusCode(RestResponse::HTTP_NOT_FOUND);
                break;
            case $throwable instanceof AccessDeniedHttpException:
                $response->setStatusCode(RestResponse::HTTP_FORBIDDEN);
                break;
            case $throwable instanceof RestException:
                $response = $throwable->getRestResponse();
                break;
        }

        if ($response->getErrorResponse()->count() === 0) {
            do {
                $response->addError(ErrorMessage::fromThrowable($throwable));
            } while ($throwable = $throwable->getPrevious());
        }

        $context = SerializationContext::create();
        if ($this->kernelEnvironment === 'dev' || $this->kernelEnvironment === 'stage') {
            $contextGroups[] = 'debug';
        }

        $data = $this->serializer->serialize($response->getErrorResponse(), 'json', $context);

        $event->setResponse($response->setContent($data));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelView', 0],
            ],
        ];
    }
}
