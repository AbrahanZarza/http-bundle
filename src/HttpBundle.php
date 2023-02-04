<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle;

use AbrahanZarza\HttpBundle\Container\Exception\ContainerBuildException;
use AbrahanZarza\HttpBundle\Container\Exception\DefinitionNotFoundException;
use AbrahanZarza\HttpBundle\Container\Injectable;
use AbrahanZarza\HttpBundle\Controller\Default\DefaultNotFoundController;
use AbrahanZarza\HttpBundle\Controller\NotFoundControllerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class HttpBundle implements HttpKernelInterface
{
    use Injectable;

    private RouteCollection $routes;
    private NotFoundControllerInterface $notFoundController;

    /** @throws ContainerBuildException */
    public function __construct()
    {
        $this->setupContainer();
        $this->routes = new RouteCollection();
        $this->setNotFoundDefaultController();
    }

    /**
     * @throws DefinitionNotFoundException
     * @throws ResourceNotFoundException
     */
    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $attributes = $matcher->match($request->getPathInfo());
        } catch (ResourceNotFoundException) {
            return ($this->notFoundController)($request);
        }

        try {
            $controller = $this->container->get($attributes['_controller']);
        } catch (Exception $e) {
            throw DefinitionNotFoundException::build($attributes['_controller'], $e);
        }

        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $request->attributes->add(array_merge($attributes, $body));

        if (!empty($attributes['_middleware'])) {
            try {
                $middleware = $this->container->get($attributes['_middleware']);
            } catch (Exception $e) {
                throw DefinitionNotFoundException::build($attributes['_middleware'], $e);
            }

            $middleware($request);
        }

        return $controller($request);
    }

    public function route(string $method, string $path, string $controller, ?string $middleware = null): void
    {
        $keyWord = $this->buildRouteKeyWord($method, $path);

        $this->routes->add($keyWord, new Route(
            $path,
            [
                '_controller' => $controller,
                '_middleware' => $middleware
            ],
            [],
            [],
            null,
            [],
            $method
        ));
    }

    /** @throws DefinitionNotFoundException */
    public function setNotFoundController(string $notFoundController): void
    {
        try {
            $this->notFoundController = $this->container->get($notFoundController);
        } catch (Exception $e) {
            throw DefinitionNotFoundException::build($notFoundController, $e);
        }
    }

    private function setNotFoundDefaultController(): void
    {
        $this->notFoundController = new DefaultNotFoundController();
        $this->container->set($this->notFoundController::class, static fn() => $this->notFoundController);
    }

    private function buildRouteKeyWord(string $method, string $path): string
    {
        return sprintf('%s_%s', $method, $path);
    }
}