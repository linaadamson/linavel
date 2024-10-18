<?php

namespace Core\Routing;

use Core\Http\Request;

class Router
{
    protected $routes;
    protected $current_request;

    public function __construct()
    {
        $this->current_request = new Request();
    }

    public function get($uri, $action)
    {
        return $this->addRoute($this->current_request::METHOD_GET, $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute($this->current_request::METHOD_POST, $uri, $action);
    }

    public function run()
    {
        try {
            $route = $this->findRoute($this->current_request->getMethod(), $this->current_request->getParsedPath());
            if (!$route) {
                throw new \Exception('Route not found', 404);
            }
            echo $route->dispatchAction($this->current_request);
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }

    protected function addRoute($method, $uri, $action)
    {
        $route = new Route($method, $uri, $action);
        $this->routes[$method][$uri] = $route;
        return $route;
    }

    protected function findRoute($method, $path)
    {
        foreach ($this->routes[$method] as $uri => $route_obj) {
            $route_regex = $route_obj->getPathRegex();

            if (preg_match($route_regex, $path, $matches)) {
                array_shift($matches);
                $param_values = $matches ?? [];
                $param_names = [];

                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $uri, $matches)) {
                    $param_names = $matches[1];
                }
                $route_params = array_combine($param_names, $param_values);
                return $route_obj->setRouteParams($route_params);
            }
        }
        return null;
    }
}