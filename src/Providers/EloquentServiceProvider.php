<?php

namespace Phpdominicana\Lightwave\Providers;

use Phpdominicana\Lightwave\Application;

class EloquentServiceProvider
{
    public function register(Application $app): void
    {
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $database = $app->getConfig()->get('database');
        $connection = $database['connections'][$database['default']];
        $capsule->addConnection($connection);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
