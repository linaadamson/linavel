<?php
namespace App\Controllers;

use Core\Http\Request;

class TodoController
{
    protected $todos;
    /** @var Request */
    protected $request;

    public function __construct()
    {
        $this->todos = [];
        $this->request = new Request();
    }

    public function addTodo()
    {
        $params = $this->request->getRequestBody();
        $this->todos[] = $params;
        return json_encode($this->todos);
    }

    public function getTodos()
    {
        return json_encode($this->todos);
    }

    public function getOne($id, $name)
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