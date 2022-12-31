<?php

use App\Produtos\src\Controller\ProdutoController;

const MAIN_LAYOUT = '/../../Core/view/layout.phtml';
$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
    case '/produtos' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->index();
     break;
     case '/produtos/save' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->save();
     break;
     case '/produtos/delete' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->delete();
     break;
    case '/' :
        $layout = null;
        $data = [];
        break;
    default:
        $layout = null;
        $data = [];
    break;  
}

require_once __DIR__ .MAIN_LAYOUT;



