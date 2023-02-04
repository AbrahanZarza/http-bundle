<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\Test;

use AbrahanZarza\HttpBundle\Container\Exception\DefinitionNotFoundException;
use AbrahanZarza\HttpBundle\Controller\Default\DefaultNotFoundController;
use AbrahanZarza\HttpBundle\HttpBundle;
use AbrahanZarza\HttpBundle\TestResources\Container\ContainerAwareTestCase;
use AbrahanZarza\HttpBundle\TestResources\Controller\SuccessController;
use AbrahanZarza\HttpBundle\TestResources\Middleware\SampleMiddleware;
use AbrahanZarza\HttpBundle\TestResources\MotherObject\ResponseMotherObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpBundleTest extends ContainerAwareTestCase
{
    /** @dataProvider handleWorksProvider */
    public function testHandleWorks(
        Response $response,
        string $method,
        string $path,
        string $controller,
        ?string $middleware = null
    ): void {
        $request = Request::create($path, $method);

        $sut = $this->sut();
        $sut->container->set($controller, new $controller());

        if (!empty($middleware)) {
            $sut->container->set($middleware, new $middleware());
        }

        $sut->route($method, $path, $controller, $middleware);

        $result = $sut->handle($request);
        ResponseMotherObject::normalizeResponseDate($result);

        $this->assertEquals($response, $result);
    }

    public function handleWorksProvider(): array
    {
        return [
            'Without middleware' => [
                ResponseMotherObject::success(),
                Request::METHOD_GET,
                '/test',
                SuccessController::class
            ],
            'With middleware' => [
                ResponseMotherObject::success(),
                Request::METHOD_GET,
                '/test',
                SuccessController::class,
                SampleMiddleware::class
            ],
        ];
    }

    /** @dataProvider handleRaisesResourceNotFoundExceptionProvider */
    public function testHandleRaisesResourceNotFoundException(
        Response $response,
        string $method,
        string $path
    ): void {
        $request = Request::create($path, $method);

        $sut = $this->sut();

        $result = $sut->handle($request);
        ResponseMotherObject::normalizeResponseDate($result);

        $this->assertEquals($response, $result);
    }

    public function handleRaisesResourceNotFoundExceptionProvider(): array
    {
        return [
            [
                ResponseMotherObject::notFound(),
                Request::METHOD_GET,
                '/test'
            ]
        ];
    }

    /** @dataProvider handleRaisesControllerDefinitionNotFoundExceptionProvider */
    public function testHandleRaisesControllerDefinitionNotFoundException(
        string $method,
        string $path,
        string $controller
    ): void {
        $this->expectException(DefinitionNotFoundException::class);

        $request = Request::create($path, $method);

        $sut = $this->sut();
        $sut->route($method, $path, $controller);

        $sut->handle($request);
    }

    public function handleRaisesControllerDefinitionNotFoundExceptionProvider(): array
    {
        return [
            [
                Request::METHOD_GET,
                '/test',
                'NotDefinedController'
            ]
        ];
    }

    /** @dataProvider handleRaisesMiddlewareDefinitionNotFoundExceptionProvider */
    public function testHandleRaisesMiddlewareDefinitionNotFoundException(
        string $method,
        string $path,
        string $controller,
        string $middleware
    ): void {
        $this->expectException(DefinitionNotFoundException::class);

        $request = Request::create($path, $method);

        $sut = $this->sut();
        $sut->container->set($controller, new $controller());
        $sut->route($method, $path, $controller, $middleware);

        $sut->handle($request);
    }

    public function handleRaisesMiddlewareDefinitionNotFoundExceptionProvider(): array
    {
        return [
            [
                Request::METHOD_GET,
                '/test',
                SuccessController::class,
                'NotFoundMiddleware'
            ]
        ];
    }

    /**
     * @dataProvider setNotFoundControllerProvider
     * @throws DefinitionNotFoundException
     */
    public function testSetNotFoundController(
        Response $response,
        string $method,
        string $path,
        string $controller
    ): void {
        $request = Request::create($path, $method);

        $sut = $this->sut();
        $sut->container->set($controller, new $controller());

        $sut->setNotFoundController($controller);

        $result = $sut->handle($request);
        ResponseMotherObject::normalizeResponseDate($result);

        $this->assertEquals($response, $result);
    }

    public function setNotFoundControllerProvider(): array
    {
        return [
            [
                ResponseMotherObject::notFound(),
                Request::METHOD_GET,
                '/test',
                DefaultNotFoundController::class
            ]
        ];
    }

    /** @dataProvider setNotFoundControllerRaisesDefinitionNotFoundExceptionProvider */
    public function testSetNotFoundControllerRaisesDefinitionNotFoundException(
        string $method,
        string $path,
        string $controller
    ): void {
        $this->expectException(DefinitionNotFoundException::class);

        $request = Request::create($path, $method);

        $sut = $this->sut();
        $sut->setNotFoundController($controller);

        $sut->route($method, $path, $controller);

        $sut->handle($request);
    }

    public function setNotFoundControllerRaisesDefinitionNotFoundExceptionProvider(): array
    {
        return [
            [
                Request::METHOD_GET,
                '/test',
                'NotFoundController'
            ]
        ];
    }

    private function sut(): HttpBundle
    {
        return new HttpBundle();
    }
}