<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

function d($variable)
{
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    // не забыть убрать !
    if (is_array($errors)) {
        $params = [
            'url' => [
                'name' => $urlName
            ],
            'errors' => $errors['totalNameUrl']
        ];
        $this->get('flash')->addMessage('success', 'Некорректный URL');
        return $this->get('renderer')->render($response, 'index.phtml', $params);
    }

    $urlData = parse_url($urlName);
    $normalizedUrl = strtolower("{$urlData['scheme']}://{$urlData['host']}");
    d($normalizedUrl);
    $url = new Database();
    $isUnique = $url->isUrlUnique($normalizedUrl);

    if ($isUnique) {
        echo "уже существует";
        $id = $isUnique['id'];
        $this->get('flash')->addMessage('success', 'Страница уже существует');
    } else {
        echo "добавлена";
//        $url->save($normalizedUrl);
//        $id = $url->isUrlUnique($normalizedUrl);

        $this->get('flash')->addMessage('success', 'Страница успешно добавлена');
    }

    return $this->get('renderer')->withRedirect("/urls/{$id}", 302);
});

$app->get('/urls/{id}', function ($request, $response, array $args) {
    $flash = $this->get('flash')->getMessages();

    $params = [
//       'url' => $url,
        'flash' => $flash
    ];
    return $this->get('renderer')->render($response, 'show.phtml', $params);
});

$app->get('/urls', function ($request, $response) {
    $flash = $this->get('flash')->getMessages();

    $params = [
        // 'urls' => $urls
        'flash' => $flash
    ];
    return $this->get('renderer')->render($response, 'show.phtml', $params);
});


$app->run();
