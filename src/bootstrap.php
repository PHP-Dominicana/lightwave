<?php declare(strict_types = 1);

namespace Phpdominicana\Lightwave;

use Dotenv\Dotenv;
use Pimple\Container;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(dirName(__DIR__) );
$dotenv->load();

$container = new Container();
$app = new Application(
    $container,
    Config::makeFromDir(dirName(__DIR__ ) . '/config/')
);

$container['app'] = fn () => $app;

$app->run();
