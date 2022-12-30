<?php

use App\Produtos\src\Controller\ProdutoController;

const MAIN_LAYOUT = '/../../Core/view/layout.php';
$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
    case '/produtos' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->index();

        require_once __DIR__ .MAIN_LAYOUT;
     break;
    case '/' :
        $layout = null;
        $data = [];
        require_once __DIR__ .MAIN_LAYOUT;
        break;
    default:
       
    break;
}



