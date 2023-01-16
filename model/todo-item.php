<?php

class TodoItem
{
  public $id;
  public $text;
  public $user;

  public function __construct($id, $text, $user)
  {
    $this->id = $id;
    $this->text = $text;
    $this->user = $user;
  }
}