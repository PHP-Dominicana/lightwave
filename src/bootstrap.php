<?php declare(strict_types = 1);

namespace Phpdominicana\Lightwave;

use Phpdominicana\Lightwave\Controllers\HelloController;
use Phpdominicana\Lightwave\Controllers\HomeController;
use Phpdominicana\Lightwave\Providers\AppServiceProvider;
use Symfony\Component\Dotenv\Dotenv;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$container = new Container();
$psrContainer = new PsrContainer($container);
$app = new Application(
    [
        AppServiceProvider::class,
    ],
    $psrContainer,
    Config::makeFromDir(__DIR__ . '/Config/'));

$app->get('helloWorld', '/hello/{name}', ['_controller' => [HelloController::class, 'index']]);
$app->get('home', '/', ['_controller' => [HomeController::class, 'index']]);

$container['app'] = fn () => $app;

$app->run();
