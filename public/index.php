<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
})->setName('main');





$app->run();
