<?php

namespace Phpdominicana\Lightwave;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    protected RouteCollection $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    protected function addRoute(string $method, string $path, array $controller): void
    {
        // Attempt to generate a unique name for the route.
        // This is a simple approach; more robust generation might be needed for complex apps.
        $name = strtolower($method) . '_' . trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $path), '_');
        if (empty($name)) {
            $name = 'route_' . uniqid(); // Fallback for root or very simple paths
        }
        
        $route = new Route($path, ['_controller' => $controller]);
        $route->setMethods([strtoupper($method)]);
        $this->routes->add($name, $route);
    }

    public function get(string $path, array $controller): void
    {
        $this->addRoute('GET', $path, $controller);
    }

    public function post(string $path, array $controller): void
    {
        $this->addRoute('POST', $path, $controller);
    }

    public function put(string $path, array $controller): void
    {
        $this->addRoute('PUT', $path, $controller);
    }

    public function delete(string $path, array $controller): void
    {
        $this->addRoute('DELETE', $path, $controller);
    }

    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }
}
