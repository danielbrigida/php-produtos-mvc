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
        try {
            $params = $this->getPost();
            $id = $this->itemDePedidoService->createItem($params);
            $this->jsonResponse($this->itemDePedidoService->getItemById($id));
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        } 
    }

    public function update()
    {
        try {
            $data = $this->getPost();
            $params = $this->getParams();
            $id = $this->itemDePedidoService->updateItem($data,$params['id']);

            $this->jsonResponse($this->itemDePedidoService->getItemById($id));
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        } 

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
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $pedido = $this->itemDePedidoService->getItemById($id);
           
            $this->jsonResponse([
                'data' =>  [
                    'success' =>  $this->itemDePedidoService->deleteById($id),
                    'valor_total_pedido' => $this->itemDePedidoService->getValorTotalDoPedido($pedido['pedido_id'])
                ]    
            ]);
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        }   
    }
}