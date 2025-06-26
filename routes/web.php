<?php

use Phpdominicana\Lightwave\Controllers\HelloController;
use Phpdominicana\Lightwave\Controllers\HomeController;
use Phpdominicana\Lightwave\Router;

/** @var Router $router */
if (!isset($router) || !($router instanceof Router)) {
 
    return;
}

$router->get('/hello/{name}', [HelloController::class, 'index']);
$router->get('/', [HomeController::class, 'index']);
