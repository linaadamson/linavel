<?php

namespace Core\Routing;

class Route
{
    protected $controller;
    protected $action;
    protected $method;
    protected $uri;
    protected $route_params;

    public function __construct($method, $uri, $controller, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->controller = $controller;
        $this->action = $action;
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
        $class = "App\\Controllers\\$this->controller";

        if (!class_exists($class)) {
            throw new \Exception("$class does not exist", 500);
        }

        if (method_exists($class, $this->action)) {
            $reflection_method = new \ReflectionMethod($class, $this->action);
            $method_params = $reflection_method->getParameters();
            $this->route_params['request'] = $request;
            $args = [];

            foreach ($method_params as $param) {
                $name = $param->getName();
                if (array_key_exists($name, $this->route_params)) {
                    $args[] = $this->route_params[$name];
                } elseif ($param->isOptional()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw new \Exception("Parameter $name  does not exist on $this->action");
                }
            }

            return $reflection_method->invokeArgs(new $class(), $args);
        } else {
            throw new \Exception("Action $this->action not found in controller $this->controller", 500);
        }
    }
}