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

Routes are defined in the `routes/web.php` file. This file is loaded by the `RouteServiceProvider`.

To define routes, you can use the `$router` object, which is an instance of `Phpdominicana\Lightwave\Router`. It provides simple methods for common HTTP verbs:

```php
<?php

// routes/web.php

use Phpdominicana\Lightwave\Controllers\HelloController;
use Phpdominicana\Lightwave\Controllers\HomeController;
use Phpdominicana\Lightwave\Router;

/** @var Router $router */

// Example: GET route with a parameter
$router->get('/hello/{name}', [HelloController::class, 'index']);

// Example: GET route for the homepage
$router->get('/', [HomeController::class, 'index']);

// Example: POST route
// $router->post('/users', [UserController::class, 'store']);

// Example: PUT route
// $router->put('/users/{id}', [UserController::class, 'update']);

// Example: DELETE route
// $router->delete('/users/{id}', [UserController::class, 'destroy']);
```

### Controller Structure and Dependency Injection

Controller methods referenced in your routes can receive dependencies in two main ways:

1.  **Route Parameters**: Any parameters defined in your route path (e.g., `{name}` in `/hello/{name}`) will be passed to your controller method as arguments with matching names.

    ```php
    // In HelloController.php
    public static function index(string $name): Response
    {
        // $name will contain the value from the URL
        return new Response("Hello {$name}");
    }
    ```

2.  **Service Container**: If your controller method type-hints `Pimple\Psr11\Container` (or its interface `Psr\Container\ContainerInterface`), the application's service container will be injected. You can use this to resolve other services, like the view renderer.

    ```php
    // In HomeController.php
    use Pimple\Psr11\Container;
    use Symfony\Component\HttpFoundation\Response;

    public static function index(Container $container): Response
    {
        $view = $container->get('view'); // Assuming 'view' service is registered
        return new Response($view->render('welcome.twig'));
    }
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
