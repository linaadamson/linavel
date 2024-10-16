<?php
namespace App\Controllers;

use Core\Http\Request;

class TodoController
{
    protected $todos;

    public function __construct()
    {
        $this->todos = [];
    }

    public function addTodo(Request $request)
    {
        $params = $request->getRequestBody();
        $this->todos[] = $params;
        return json_encode($this->todos);
    }

    public function getTodos()
    {
        return json_encode($this->todos);
    }

    public function getOne($name, $id)
    {
        echo $id;
        echo $name;
        return json_encode($this->todos);
    }

    public function test()
    {
        echo 'working';
    }
}