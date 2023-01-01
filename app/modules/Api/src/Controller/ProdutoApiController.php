<?php

namespace App\Api\src\Controller;

use App\Api\src\Controller\ApiController;
use App\Produtos\src\Service\ProdutoService;

class ProdutoApiController extends ApiController
{
    private $produtoService;

    public function __construct()
    {
       $this->produtoService =  new ProdutoService();
    }

    public function create()
    {
        $this->jsonError("method not implemented");
    }

    public function update()
    {
        $this->jsonError("method not implemented");
    }

    public function index()
    {
        try {
            $params = $this->getParams();
            $pedidoId = $params['pedido_id'] ?? null;
            $id = $params['id'] ?? null;

            if($pedidoId) {
                $this->jsonResponse($this->produtoService->getProdutosDisponiveisNoPedido($pedidoId));
            }    

            $this->jsonResponse($this->produtoService->getItemById($id));
        } catch (\Exception $exception) {
           $this->jsonError($exception->getMessage());
        }    
    }

    public function delete()
    {
        $this->jsonError("method not implemented");
    }
}