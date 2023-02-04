<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Test\Controller\Default;

use AbrahanZarza\HttpBundle\Controller\Default\DefaultNotFoundController;
use AbrahanZarza\HttpBundle\TestResources\MotherObject\ResponseMotherObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultNotFoundControllerTest extends TestCase
{
    /** @dataProvider invokeWorksProvider */
    public function testInvokeWorks(Request $request, Response $response): void
    {
        $sut = $this->sut();

        $result = $sut($request);
        ResponseMotherObject::normalizeResponseDate($result);

        $this->assertEquals($response, $result);
    }

    public function invokeWorksProvider(): array
    {
        return [
            [Request::createFromGlobals(), ResponseMotherObject::notFound()]
        ];
    }

    private function sut(): DefaultNotFoundController
    {
        return new DefaultNotFoundController();
    }
}