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

    public function get($uri, $controller, $action)
    {
        return $this->addRoute('GET', $uri, $controller, $action);
    }

    public function post($uri, $controller, $action)
    {
        return $this->addRoute('POST', $uri, $controller, $action);
    }

    public function run()
    {
        try {

            $route = $this->findRoute($this->current_request->getMethod(), $this->current_request->getPath());

            if (!$route) {
                throw new \Exception('Route not found', 404);
            }

            $class = "App\\Controllers\\$route->controller";

            if (!class_exists($class)) {
                throw new \Exception("$class does not exist", 500);
            }

            $action = $route->action;
            if (method_exists($class, $action)) {
                $object = new $class();
                echo $object->$action($this->current_request);
            } else {
                throw new \Exception("Action $action not found in controller $route->controller", 500);
            }
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
    }

    /**
     * @return \Core\Routing\Route
     */
    protected function addRoute($method, $uri, $controller, $action)
    {
        $route = new Route($method, $uri, $controller, $action);
        $this->routes[$method][$uri] = $route;
        return $route;
    }

    protected function findRoute($method, $path)
    {
        foreach ($this->routes[$method] as $uri => $route_obj) {
            if ($path == $uri) {
                return $route_obj;
            }
        }

        return null;
    }
}