<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;
use Phpdominicana\Lightwave\Controllers\HelloController;
use Phpdominicana\Lightwave\Controllers\HomeController;
use Pimple\Psr11\Container as Psr11Container;
use Symfony\Component\Routing\Route;

class RouteServiceProvider implements ProviderInterface
{

    #[\Override] public function register(Application $app): void
    {
        $app->get('hello', new Route('/hello/{name}', ['_controller' => [HelloController::class, 'index']]));
        $app->get('home',
            new Route(
                '/',
                [
                    '_controller' => [HomeController::class, 'index'],
                    'container' => new Psr11Container($app->getInjector())
                ]
            )
        );
    }
}
