<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

function d($variable) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}

if (session_status() == PHP_SESSION_NONE) session_start();

$container = new Container();

$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);


$app->get('/', function ($request, $response) {
   // $flash = $this->get('flash')->getMessages();
    $params = [
        'url' => [
            'name' => ''
        ],
        'errors' => [],
        //'flash' => $flash
    ];
    return $this->get('renderer')->render($response, 'index.phtml', $params);
});

$app->post('/urls', function ($request, $response) {

    $url = $request->getParsedBodyParam('url');
    $urlName = htmlspecialchars($url['name']);
    $validator = new Valitron\Validator(['totalNameUrl' => $urlName]);
    $validator->rule('url', 'totalNameUrl')->message('Url не действительный ');
    $validator->rule('required', 'totalNameUrl')->message('Url не должен быть пустым');
    $validator->rule('lengthMax', 'totalNameUrl', 255)->message('Url не должен превышать 255 символов');
    $validator->validate();

    $errors = $validator->errors();
    if (is_array($errors)) {

        $params = [
            'url' => [
                'name' => $urlName
            ],
            'errors' => $errors['totalNameUrl']
        ];
        // $this->get('flash')->addMessage('success', 'error has occurred');
        return $this->get('renderer')->render($response, 'index.phtml', $params);
    }

   // $urlData = parse_url($formData['name']);
    // $normalizedUrl = strtolower("{$urlData['scheme']}://{$urlData['host']}");

    return $this->get('renderer')->withRedirect('/urls', 302);
});

$app->get('/urls', function ($request, $response) {
    $params = [];
    return $this->get('renderer')->render($response, 'show.phtml', $params);
})->setName('urls');

$app->run();
