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

    case '/arquivos-de-produtos/upload' :
        $render = new ArquivoDeProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->upload();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;

    case '/arquivos-de-produtos/delete' :
        $render = new ArquivoDeProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->delete();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;

    case '/arquivos-de-produtos/download' :
        $render = new ArquivoDeProdutoController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->download();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
    break;
    default:
       
    break;  
}





