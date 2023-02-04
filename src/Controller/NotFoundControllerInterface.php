<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface NotFoundControllerInterface
{
    public function __invoke(Request $request): Response;
}