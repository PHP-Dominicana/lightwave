<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;
use Pimple\Container;

class TwigServiceProvider implements ProviderInterface
{
    #[\Override] public function register(Application $app): void
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/resources/views');
        $app->getInjector()['view'] = fn () => new \Twig\Environment($loader);
    }
}
