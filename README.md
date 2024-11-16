# HTTP Bundle

![PHP](https://img.shields.io/badge/php-8.3-blue)
![Docker](https://img.shields.io/badge/docker-latest-lightblue)

This library provides a micro HTTP framework with basics, routing and dependency injection container.

## Setup

For using this library in your proyect, install it via [composer](https://getcomposer.org/) or add it manually in your `composer.json` file.
```
composer require abrahanzarza/http-bundle
```
This will install the latest version in your project.

## Usage

### Basic configuration

Here is an extract of code for basic configuration usage. At this example we can see that our application is a 
`HttpBundle` instance. This instance receive a `Symfony\Component\HttpFoundation\Request` request param and returns a
`Symfony\Component\HttpFoundation\Response` that is returned to the client.

```
<?php

declare(strict_types=1);

include_once __DIR__ . '/../vendor/autoload.php';

use AbrahanZarza\HttpBundle\HttpBundle;
use Symfony\Component\HttpFoundation\Request;

$app = new HttpBundle();

// Define your dependencies
// Define your routes

$request = Request::createFromGlobals();
$response = $app->handle($request);

$response->send();
```

The next thing is define dependencies and routes that we can se how to build after.

### Defining dependencies

To define dependencies use [PHP-DI](https://php-di.org/doc/getting-started.html) syntax.
```
$app->container->set(
    ClassName::class, 
    static fn(ContainerInterface $c) => new ClassName('params...')
);
```

As we can see in the code example, we are using our `$app` DI container.

### Defining routes

To define our HTTP application routes we are using this syntax:
```
$app->route('method', 'path', 'ControllerClass');
```
If we need to use middleware before executing the controller logic, we can use:
```
$app->route('method', 'path', 'ControllerClass', 'MiddlewareClass');
```
Both classes, **Controller** and **Middleware** must implement an **__Invoke** method. An example of controller:
```
<?php

declare(strict_types=1);

namespace namespace\controllers;

class ControllerClass
{
    public function __invoke(Request $request): Response
    {
        return new Response('Success response');
    }
}
```