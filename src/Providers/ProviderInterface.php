<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;

interface ProviderInterface
{
    public function register(Application $app): void;
}
