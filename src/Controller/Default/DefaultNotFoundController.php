<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Controller\Default;

use AbrahanZarza\HttpBundle\Controller\NotFoundControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DefaultNotFoundController implements NotFoundControllerInterface
{
    public function __invoke(Request $request): Response
    {
        return new Response('Not found', Response::HTTP_NOT_FOUND);
    }
}