<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Container\Exception;

use Exception;
use Throwable;

final class ContainerBuildException extends Exception
{
    public static function build(Throwable $e): self
    {
        return new self(
            sprintf('Error during DI container building, details: %s', $e->getMessage())
        );
    }
}