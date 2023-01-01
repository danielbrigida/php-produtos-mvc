<?php

use App\Api\src\Controller\ItemDePedidoApiController;

use App\Core\constants\Layout;

$request = explode('?',$_SERVER['REQUEST_URI']);

switch ($request[0]) {
    case '/api/itens-pedidos' :
        $render = new ItemDePedidoApiController();
        [
            'layout' => $layout,
            'data' => $data
        ] = $render->api();

        require_once __DIR__ . Layout::MAIN_LAYOUT;
     break;
   
    default:
       
    break;  
}
