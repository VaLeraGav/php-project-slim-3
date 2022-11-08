<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;

$container = new Container();

$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$container->set('flash', function (){
    return new \Slim\Flash\Messages();
});


$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {

    return $this->get('renderer')->render($response, 'index.phtml');
})->setName('index');


$app->get('/urls', function ($request, $response) {

    return $this->get('renderer')->render($response, 'show.phtml');
})->setName('urls');



$app->run();
