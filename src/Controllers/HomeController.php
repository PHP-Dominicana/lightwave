<?php

namespace Phpdominicana\Lightwave\Controllers;

use Phpdominicana\Lightwave\Application;
use Pimple\Psr11\Container;
use Symfony\Component\HttpFoundation\Response;

readonly class HomeController
{
    public static function index(Container $container): Response
    {
        $view = $container->get('view');
        return new Response($view->render('welcome.twig'));
    }
}
