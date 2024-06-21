<?php

namespace Phpdominicana\Lightwave;

use ReflectionClass;
use Pimple\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    protected array $providers = [];
    protected Container $injector;

    protected Config $config;

    protected RouteCollection $routes;

    public function __construct(
        Container $injector,
        Config $config
    )
    {
        $this->providers = $config->get('App.providers');
        $this->injector = $injector;
        $this->config = $config;
        $this->routes = new RouteCollection();
    }

    public function getInjector(): Container
    {
        return $this->injector;
    }

    public function get(string $name, Route $route): void
    {
        $route->setMethods(['GET', 'HEAD']);
        $this->routes->add($name, $route);
    }

    public function post(string $name, Route $route)
    {
        $route->setMethods(['POST', 'HEAD']);
        $this->routes->add($name, $route);
    }

    public function put(string $name, Route $route)
    {
        $route->setMethods(['PUT', 'HEAD']);
        $this->routes->add($name, $route);
    }

    public function delete(string $name, Route $route)
    {
        $route->setMethods(['DELETE', 'HEAD']);
        $this->routes->add($name, $route);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @throws \ReflectionException
     */
    public function run(): void
    {
        foreach ($this->providers as $provider) {
            if (is_callable($provider)) {
                $provider = $provider();
                $provider->register($this);
            } else {
                $reflection = new ReflectionClass($provider);
                $reflection->newInstance()->register($this);
            }
        }
        // Create a context and matcher
        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());
        $matcher = new UrlMatcher($this->routes, $context);

        // Match the current request to a route
        try {
            $parameters = $matcher->match($context->getPathInfo());
            $controller = $parameters['_controller'];
            unset($parameters['_controller'], $parameters['_route']);
            $response = call_user_func_array($controller, $parameters);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred: ' . $e->getMessage(), 500);
        }

        // Send the response
        $response->send();
    }
}
