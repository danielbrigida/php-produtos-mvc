<?php

use App\Produtos\src\Controller\ProdutoController;
use App\Core\constants\Layout;

$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
    case '/produtos' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->index();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
     break;
     case '/produtos/save' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->save();

        require_once __DIR__ .Layout::MAIN_LAYOUT;
    break;
     case '/produtos/delete' :
        $render = new ProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->delete();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;
    case '/' :
        $layout = null;
        $data = [];

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;
    default:
       
    break;  
}





