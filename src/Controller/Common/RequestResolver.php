<?php

declare(strict_types=1);

namespace App\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $reflection = new \ReflectionClass($argument->getType());
        if ($reflection->implementsInterface(RequestInterface::class)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        // creating new instance of custom request DTO
        $class = $argument->getType();
        $dto = new $class();

        if ($request->isMethod('GET')) {
            $params = $request->query->all();
            $json = $this->serializer->serialize($params, 'json');
        } else {
            $json = $request->getContent();
        }

        // Deserialize request to DT entity
        $this->serializer->deserialize(
            $json,
            $class,
            'json',
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $dto
            ]
        );

        // throw bad request exception in case of invalid request data
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $message = [];

            foreach ($errors as $error) {
                $message[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new BadRequestHttpException($this->serializer->serialize($message, 'json'));
        }

        yield $dto;
    }
}