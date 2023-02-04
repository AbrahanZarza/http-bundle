<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Test\Container;

use AbrahanZarza\HttpBundle\Container\Exception\ContainerBuildException;
use AbrahanZarza\HttpBundle\Container\Injectable;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @group test
 */
class InjectableTest extends TestCase
{
    public function testSetupContainerRaisesContainerBuildException(): void
    {
        $this->expectException(ContainerBuildException::class);

        $injectableTrait = $this->getMockForTrait(Injectable::class, [], '', true, true, true, [
            'setupContainer'
        ]);

        $injectableTrait->expects(self::once())->method('setupContainer')
            ->willThrowException(ContainerBuildException::build(new Exception()));

        $injectableTrait->setupContainer();
    }
}