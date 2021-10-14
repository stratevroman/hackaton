<?php
declare(strict_types=1);


namespace App\Subscriber;

use App\Dto\ErrorMessage;
use App\Dto\RestResponse;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationList;

class Response2JsonSubscriber implements EventSubscriberInterface
{
    private Serializer $serializer;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * Serialize entity and initializes a new response object with the json content
     */
    public function responseSerializer(ViewEvent $event)
    {
        $response = RestResponse::new();
        $result = $event->getControllerResult();

        switch (true) {
            case $result instanceof NotFoundHttpException:
            case $result === null:
                $response->setStatusCode(Response::HTTP_NOT_FOUND)->addNewError('Resource not available');
                break;
            case  $result instanceof ConstraintViolationList:
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                foreach ($result as $violation) {
                    $response->addError(ErrorMessage::fromViolation($violation));
                }
                break;
            case is_array($result):
            case $this->em->contains($result):
                break;
        }

        if ($response->getErrorResponse()->count() > 0) {
            $result = $response->getErrorResponse();
        }

        $data = $this->serializer->serialize($result, 'json', $this->getSerializationContext());
        $response->setContent($data);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['responseSerializer', 0],
            ],
        ];
    }

    private function getSerializationContext(): SerializationContext
    {
        return SerializationContext::create();
    }
}
