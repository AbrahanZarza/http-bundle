<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\TestResources\Container;

use AbrahanZarza\HttpBundle\Container\Injectable;
use PHPUnit\Framework\TestCase;

class ContainerAwareTestCase extends TestCase
{
    use Injectable;

    protected function setUp(): void
    {
        $this->setupContainer();
    }
}