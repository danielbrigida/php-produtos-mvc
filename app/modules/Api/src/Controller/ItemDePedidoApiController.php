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
            $this->jsonResponse([
                'id' =>  $this->itemDePedidoService->createItem($params)
            ]);
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        } 
    }

    public function update()
    {
        try {
            $data = $this->getPost();
            $params = $this->getParams();

            $this->jsonResponse([
                'id' =>  $this->itemDePedidoService->updateItem($data,$params['id'])
            ]);
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
           
            $this->jsonResponse(['success' =>  $this->itemDePedidoService->deleteById($id)]);
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        }   
    }
}