<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\TestResources\Controller;

use AbrahanZarza\HttpBundle\TestResources\MotherObject\ResponseMotherObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SuccessController
{
    public function __invoke(Request $request): Response
    {
        return ResponseMotherObject::success();
    }
}