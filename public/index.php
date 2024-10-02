<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set('db', function () {
    $settings = [
        'host'     => '127.0.0.1',
        'dbname'   => 'slim-task',
        'username' => 'root',
        'password' => 'root',
    ];

    $pdo = new PDO(
        'mysql:host=' . $settings['host'] . ';dbname=' . $settings['dbname'],
        $settings['username'],
        $settings['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, DELETE');
});


$app->get('/posts', function ($request, $response, $args) {
    $pdo = $this->get('db');

    $stmt  = $pdo->query('SELECT * FROM posts');
    $users = $stmt->fetchAll();

    $response->getBody()->write(json_encode($users, JSON_THROW_ON_ERROR));

    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
