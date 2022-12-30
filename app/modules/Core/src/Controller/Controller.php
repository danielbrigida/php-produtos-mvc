<?php

namespace App\Core\src\Controller;

class Controller
{
  public function view(string $view, $data = []): array
  {
    return  [
        'layout' =>  __DIR__ .'\\..\\..\\..\\' . $view . '.php',
        'data'=> $data,
    ];
  }
}
