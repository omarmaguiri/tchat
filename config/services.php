<?php

return [
    'pdo' => function ($c) {
        $db = $c['database'];
        $pdo = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['name'] . ';charset=' . $db['charset'], $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    },
    'request' => function ($c) {
        return new \Core\Http\Request();
    },
    'router' => function ($c) {
        return new \Core\Router\Router();
    },
    'session' => function ($c) {
        return new \Core\Http\Session();
    },
    'security' => function ($c) {
        return new \Core\Security\Security($c->get('session'));
    },
    'UserRepository' => function ($c) {
        return new \App\Repository\UserRepository($c->get('pdo'));
    },
    'MessageRepository' => function ($c) {
        return new \App\Repository\MessageRepository($c->get('pdo'));
    },
    'passwordEncoder' => function ($c) {
        return new \App\Service\PasswordEncoder();
    },
];