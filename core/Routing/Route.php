<?php

namespace Core\Routing;
use Core\Pipeline;

class Route
{
    protected $action;
    protected $method;
    protected $uri;
    protected $route_params;
    protected $middleware;

    public function __construct($method, $uri, $action, $middleware)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
        $this->middleware = $middleware;
    }

    public function getPathRegex()
    {
        $route_regex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches) {
            return isset($matches[1]) ? "($matches[2])" : '([a-zA-Z0-9_-]+)';
        }, $this->uri);

        return "@^$route_regex$@";
    }

    public function setRouteParams($params = [])
    {
        $this->route_params = $params;
        return $this;
    }

    public function dispatchAction($request)
    {
        $this->route_params['request'] = $request;

        if (is_callable($this->action)) {
            $reflection_function = new \ReflectionFunction($this->action);
            $args = $this->getNamedParameters($reflection_function);
            return $this->invokeAction($request, $this->action, $args);
            
        } else if (is_array($this->action)) {
            [$controller, $method] = $this->action;
            $class = "App\\Controllers\\$controller";

            if (!class_exists($class)) {
                throw new \Exception("$class does not exist", 500);
            } else if (!method_exists($class, $method)) {
                throw new \Exception("Method $method not found in controller $controller", 500);
            }

            $reflection_method = new \ReflectionMethod($class, $method);
            $args = $this->getNamedParameters($reflection_method);
            return $this->invokeAction($request, [new $class, $method], $args);
        }
    }

    protected function invokeAction($request, $action, $args)
    {
        if (empty($this->middleware)) {
            return call_user_func_array($action, $args);
        }

        return (new Pipeline($request))->through($this->middleware)->then(function ($passable) use ($action, $args) {
            $this->route_params['request'] = $passable;
            return call_user_func_array($action, $args);
        });
    }

    protected function getNamedParameters($reflection)
    {
        $method_params = $reflection->getParameters();
        $args = [];
        foreach ($method_params as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $this->route_params)) {
                $args[] = $this->route_params[$name];
            } elseif ($param->isOptional()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new \Exception("Parameter $name does not exist", 500);
            }
        }

        return $args;
    }
}