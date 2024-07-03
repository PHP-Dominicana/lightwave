<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;
use Phpdominicana\Lightwave\Traits\AssetResolver;
use Pimple\Container;

class TwigServiceProvider implements ProviderInterface
{

    use AssetResolver;

    #[\Override] public function register(Application $app): void
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/resources/views');
        $twig = new \Twig\Environment($loader);

        $assets = $this->getAssets();
        $cssBundle = $assets['cssBundle'];
        $jsBundle = $assets['jsBundle'];

        $app->getInjector()['view'] = function () use ($twig, $cssBundle, $jsBundle) {
            $twig->addGlobal('cssBundle', $cssBundle);
            $twig->addGlobal('jsBundle', $jsBundle);
            return $twig;
        };
    }
}
