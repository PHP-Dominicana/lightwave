<?php declare(strict_types = 1);

namespace Phpdominicana\Lightwave;

use Dotenv\Dotenv;
use Pimple\Container;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(dirName(__DIR__) );
$dotenv->load();

$container = new Container();

$config = Config::makeFromDir(dirName(__DIR__ ) . '/config/');
$router = new Router(); // Create Router instance

$container['config'] = $config;
$container['router'] = $router;

$app = new Application(
    $container,
    $config,
    $router // Pass Router instance to Application
);

$container['app'] = fn () => $app;

$app->run();
