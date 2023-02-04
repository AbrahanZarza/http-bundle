<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\TestResources\Middleware;

use Symfony\Component\HttpFoundation\Request;

final class SampleMiddleware
{
    public function __invoke(Request $request): void
    {
    }
}