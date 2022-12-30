<?php

namespace App\Core\src\Controller;

class Controller
{
  public function view(string $view, $data = [])
  {
    require_once   __DIR__ .'\\..\\..\\..\\' . $view . '.php';
  }
}