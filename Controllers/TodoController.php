<?php

class TodoController
{
    protected $todos;

    public function __construct()
    {
        $this->todos = [];
    }

    public function addTodos($todo)
    {
        $this->todos[] = $todo;
    }

    public function getTodos()
    {
        return json_encode($this->todos);
    }
}