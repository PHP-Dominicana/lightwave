<?php

namespace Phpdominicana\Lightwave\Controllers;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public static function index(): Response
    {
        return new Response('Hello World');
    }
}
