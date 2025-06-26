<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;
use Phpdominicana\Lightwave\Router;
class RouteServiceProvider implements ProviderInterface
{

    #[\Override] public function register(Application $app): void
    {
        $router = $app->getRouter();

        (function ($router) {
            require __DIR__ . '/../../routes/web.php';
        })($router);
    }
}
