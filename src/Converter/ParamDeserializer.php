<?php

declare(strict_types=1);

namespace App\Converter;

use App\Dto\ErrorMessage;
use App\Exception\RestException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParamDeserializer implements ParamConverterInterface
{
    private Serializer $serializer;
    private ValidatorInterface $validator;

    public function __construct(Serializer $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $dto = $this->releaseDto($request->getContent(), $configuration);

        $request->attributes->set($configuration->getName(), $dto);
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'dto' === $configuration->getConverter();
    }

    private function getDeserializationContext(): DeserializationContext
    {
        $context = DeserializationContext::create();
        $context->setGroups(['Default', 'input']);

        return $context;
    }

    protected function releaseDto(string $content, ParamConverter $config)
    {
        $dto = $this->serializer->deserialize(
            $content,
            $config->getClass(),
            'json',
            $this->getDeserializationContext(),
        );

        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            $restException = new RestException();
            foreach ($violations as $violation) {
                $restException->getRestResponse()->addError(ErrorMessage::fromViolation($violation));
            }
            throw $restException;
        }

        return $dto;
    }
}
