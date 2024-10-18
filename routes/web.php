<?php

use Core\Routing\Router;

$router = new Router();

$router->get('/', function () {
    return 'Linavel Framework V1';
});
$router->get('/linavel/{version}', function ($version) {
    return "Linavel Framework V$version";
});
$router->post('/todos', ['TodoController', 'addTodo']);
$router->get('/todos', ['TodoController', 'getTodos']);
$router->get('/todos/{id:[0-9]+}/{name:[a-z]+}', ['TodoController', 'getOne']);

$router->run();