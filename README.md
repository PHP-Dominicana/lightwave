# Lightwave
This is a simple PHP starter kit created by the PHP dominicana community. It is designed to help you get started with PHP development quickly. It includes a simple web server, a simple router It is designed to be simple and easy to use.

## Installation
A few simple steps are needed to get this application up and running:

The next step assumes that composer is available in your PATH

```shell
composer create-project phpdominicana/lightwave [project-name]
cd [project-name]
```
Copy the .env.example file to .env and update the database connection settings.

```shell
cp .env.example .env
```

## Usage with PHP native server
To start the PHP native server, run the following command:

```shell
sh server.sh
```

## Add new routes
To add new routes, you need to edit the `src/Providers/RouteServiceProvider` class.

Exammples:

```php
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
$app->post('create_user',
            new Route(
                '/users',
                [
                    '_controller' => [UserController::class, 'store'],
                    'container' => new Psr11Container($app->getInjector())
                ]
            )
        );
```

## How to connect to a database using eloquent ORM

Install eloquent ORM

```shell
composer require illuminate/database
```

Add the EloquenServiceProvider to the `src/Providers/AppServiceProvider` class to the config/app.php file in the `providers` array.

```php
'providers' => [
        \Phpdominicana\Lightwave\Providers\AppServiceProvider::class,
       \Phpdominicana\Lightwave\Providers\TwigServiceProvider::class,
         \Phpdominicana\Lightwave\Providers\RouteServiceProvider::class,
         \Phpdominicana\Lightwave\Providers\EloquentServiceProvider::class
    ],
```

Then you can create you model and extend the `Illuminate\Database\Eloquent\Model` class.

```php
<?php
 
namespace Phpdominicana\Lightwave\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
}
```
