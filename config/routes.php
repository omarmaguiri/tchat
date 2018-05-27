<?php

return [
    'index' => [
        'path' => '/',
        'controller' => 'App\Controller\LoginController::login',
    ],
    'login' => [
        'path' => '/login',
        'controller' => 'App\Controller\LoginController::login',
    ],
    'signin' => [
        'path' => '/sign_in',
        'controller' => 'App\Controller\LoginController::signIn',
        'method' => 'POST',
    ],
    'register' => [
        'path' => '/register',
        'controller' => 'App\Controller\LoginController::register',
        'method' => 'POST',
    ],
    'logout' => [
        'path' => '/logout',
        'controller' => 'App\Controller\LoginController::logout',
    ],
    'home' => [
        'path' => '/homepage',
        'controller' => 'App\Controller\HomeController::index',
    ],
    'messages_list' => [
        'path' => '/messages',
        'controller' => 'App\Controller\MessageController::listMessages',
        'method' => 'GET',
    ],
    'message_add' => [
        'path' => '/messages/add',
        'controller' => 'App\Controller\MessageController::addMessages',
        'method' => 'POST',
    ],
    'users_list' => [
        'path' => '/users',
        'controller' => 'App\Controller\HomeController::listUsers',
        'method' => 'GET',
    ],
];