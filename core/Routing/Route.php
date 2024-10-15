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

    public function dispatchAction()
    {
        $class = "App\\Controllers\\$this->controller";

        if (!class_exists($class)) {
            throw new \Exception("$class does not exist", 500);
        }

        if (method_exists($class, $this->action)) {
            return call_user_func_array([new $class, $this->action], $this->route_params);
        } else {
            throw new \Exception("Action $this->action not found in controller $this->controller", 500);
        }
    }
}