<?php declare(strict_types = 1);

namespace Phpdominicana\Lightwave;

use Dotenv\Dotenv;
use Phpdominicana\Lightwave\Controllers\HelloController;
use Phpdominicana\Lightwave\Controllers\HomeController;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Symfony\Component\Routing\Route;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(dirName(__DIR__) );
$dotenv->load();

$container = new Container();
$app = new Application(
    $container,
    Config::makeFromDir(__DIR__ . '/Config/'));

$container['app'] = fn () => $app;

$app->run();
