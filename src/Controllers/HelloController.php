<?php

namespace Phpdominicana\Lightwave\Controllers;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public static function index($name): Response
    {
        return new Response(sprintf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8')));
    }
}
