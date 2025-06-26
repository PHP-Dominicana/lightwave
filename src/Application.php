<?php

namespace Phpdominicana\Lightwave;

use ReflectionClass;
use Pimple\Container;
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
    protected Router $router;

    public function __construct(
        Container $injector,
        Config $config,
        Router $router
    ) {
        $this->providers = $config->get('app.providers');
        $this->injector = $injector;
        $this->config = $config;
        $this->router = $router;
    }

    public function getInjector(): Container
    {
        return $this->injector;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @throws \ReflectionException
     */
    public function run(): void
    {
        // Register service providers
        foreach ($this->providers as $provider) {
            $instance = is_string($provider) ? new $provider() : $provider();
            $instance->register($this);
        }

        // Create a context and matcher
        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());
        $matcher = new UrlMatcher($this->router->getRoutes(), $context);

        // Match the current request to a route
        try {
            $parameters = $matcher->match($context->getPathInfo());
            $controllerAction = $parameters['_controller'];
            $routeParams = $parameters;
            unset($routeParams['_controller'], $routeParams['_route']);

            $argsToPass = [];
            if (is_array($controllerAction) && count($controllerAction) === 2) {
                $controllerClass = $controllerAction[0];
                $methodName = $controllerAction[1];
                $reflectionMethod = \ReflectionMethod::createFromMethodName("{$controllerClass}::{$methodName}");

                foreach ($reflectionMethod->getParameters() as $param) {
                    $paramType = $param->getType();
                    if ($paramType && !$paramType->isBuiltin()) {
                        $typeName = $paramType->getName();
                        if ($typeName === \Pimple\Psr11\Container::class || is_subclass_of($typeName, \Psr\Container\ContainerInterface::class)) {
                            // Check if injector already has a Psr11Container, or wrap it
                            if ($this->injector->offsetExists('psr11_container')) {
                                $argsToPass[$param->getName()] = $this->injector['psr11_container'];
                            } else {
                                // It's generally better to register the Psr11Container once if it's used often.
                                // For now, creating it on-the-fly for simplicity.
                                $argsToPass[$param->getName()] = new \Pimple\Psr11\Container($this->injector);
                            }
                            continue;
                        }
                    }
                    // Try to fill from route parameters
                    if (array_key_exists($param->getName(), $routeParams)) {
                        $argsToPass[$param->getName()] = $routeParams[$param->getName()];
                    } elseif ($param->isDefaultValueAvailable()) {
                        $argsToPass[$param->getName()] = $param->getDefaultValue();
                    } else {
                        // Potentially throw error or handle missing required parameters not available in route
                    }
                }
            } else {
                // Non-class@method callable, pass route params directly
                $argsToPass = $routeParams;
            }

            $response = call_user_func_array($controllerAction, $argsToPass);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred: ' . $e->getMessage(), 500);
        }

        // Send the response
        $response->send();
    }
}
