<?php

use App\Produtos\src\Controller\ProdutoController;


$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
    case '/produtos' :
        $render = new ProdutoController();
        $render->index();
     break;
    case '/about' :
        require __DIR__ . '/views/about.php';
        break;
    default:
       
    break;
}
