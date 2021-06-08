<?php

declare(strict_types=1);

namespace App\Controller\Common;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();
        $response->setContent($exception->getMessage());
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof BadRequestHttpException) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
//            $response->headers->replace($exception->getHeaders());
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}