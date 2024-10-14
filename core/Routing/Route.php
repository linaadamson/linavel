<?php

namespace Core\Routing;

class Route
{
    public $method;
    public $uri;
    public $controller;
    public $action;

    public function __construct($method, $uri, $controller, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->controller = $controller;
        $this->action = $action;
    }
}