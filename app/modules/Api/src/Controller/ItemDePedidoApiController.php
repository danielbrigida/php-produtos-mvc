<?php

namespace App\Api\src\Controller;

use App\Api\src\Controller\ApiController;
use App\Produtos\src\Service\ItemDePedidoService;

class ItemDePedidoApiController extends ApiController
{
    private $itemDePedidoService;

    public function __construct()
    {
       $this->itemDePedidoService =  new ItemDePedidoService();
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function index()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;

            $this->jsonResponse($this->itemDePedidoService->getItemById($id));
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        }    

    }

    public function delete()
    {
        echo "delete"; die;
    }
}