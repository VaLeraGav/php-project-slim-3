<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

$container = new Container();

$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

//$databaseUrl = parse_url($_ENV['DATABASE_URL']);
//dump($databaseUrl);


$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

$app->get('/', function ($request, $response) {
    $params = [
        'url' => [
            'name' => ''
        ],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, 'index.phtml', $params);
})->setName('index');


$app->get('/urls', function ($request, $response) {
    $params = [];
    return $this->get('renderer')->render($response, 'show.phtml', $params);
})->setName('urls');


$app->post('/urls', function ($request, $response) {
    return $this->get('renderer')->withRedirect('/urls', 302);
});

$app->run();
