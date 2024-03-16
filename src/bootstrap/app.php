<?php

declare(strict_types=1);

namespace ApiCustomerManager\bootstrap;

use Closure;
use Dotenv;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Http\HttpRequest;
use Http\HttpResponse;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function FastRoute\simpleDispatcher;

require __DIR__ . '/../../vendor/autoload.php';

error_reporting(E_ALL);

/**
 *  Definir o cabeçalho
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

/**
 * Carregar variáveis de ambiente do arquivo .env
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$environment = 'development';

/**
 * Register the error handler
 */
$whoops = new Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new PrettyPageHandler);
} else {
    $whoops->pushHandler(function ($e) {
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();

$request = new HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$response = new HttpResponse();

foreach ($response->getHeaders() as $header) {
    header($header, false);
}

echo $response->getContent();

$routeDefinitionCallback = function (RouteCollector $r) {
    $routes = include('src/routes/api.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $args = $routeInfo[2];

        /**
         * Verificar se o manipulador é uma função anônima (Closure)
         */
        if ($handler instanceof Closure) {
            /**
             * Invocar a função anônima passando $args como argumento
             */
            $handler($args);
        } else {
            /**
             * Se não for uma função anônima, tratar o erro apropriadamente
             */
            $response->setContent('500 - Internal Server Error');
            $response->setStatusCode(500);
        }
        break;
}
