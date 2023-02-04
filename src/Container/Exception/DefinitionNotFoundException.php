<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Container\Exception;

use Exception;
use Throwable;

final class DefinitionNotFoundException extends Exception
{
    public static function build(string $definition, Throwable $e): self
    {
        return new self(
            sprintf('DI definition: %s not found, details: %s', $definition, $e->getMessage())
        );
    }
}