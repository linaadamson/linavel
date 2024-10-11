<?php

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

    public function getTodos(Request $request)
    {
        return json_encode($this->todos);
    }
}