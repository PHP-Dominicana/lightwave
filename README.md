# Lightwave
This is a simple PHP starter kit created by the PHP dominicana community. It is designed to help you get started with PHP development quickly. It includes a simple web server, a simple router It is designed to be simple and easy to use.

## Installation
A few simple steps are needed to get this application up and running:

The next step assumes that composer is available in your PATH

```shell
composer create-project phpdominicana/lightwave [project-name]
cd [project-name]
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
