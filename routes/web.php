<?php

use Core\Routing\Router;

$router = new Router();

$router->get('/', 'TodoController', 'test');
$router->post('/todos', 'TodoController', 'addTodo');
$router->get('/todos', 'TodoController', 'getTodos');
$router->get('/todos/{id:[0-9]+}/{name:[a-z]+}', 'TodoController', 'getOne');

$router->run();