<?php

use App\Arquivos\src\Controller\ArquivoDeProdutoController;
use App\Core\constants\Layout;

$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
     case '/arquivos-de-produtos/save' :
        $render = new ArquivoDeProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->save();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;
    default:
       
    break;  
}





