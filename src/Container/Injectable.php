<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Container;

use AbrahanZarza\HttpBundle\Container\Exception\ContainerBuildException;
use DI\Container;
use DI\ContainerBuilder;
use Exception;

trait Injectable
{
    public Container $container;

    /** @throws ContainerBuildException */
    public function setupContainer(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);

        try {
            $this->container = $builder->build();
        } catch (Exception $e) {
            throw ContainerBuildException::build($e);
        }
    }
}