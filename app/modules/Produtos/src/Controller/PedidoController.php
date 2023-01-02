<?php

namespace App\Produtos\src\Controller;

use App\Core\src\Controller\Controller;
use App\Produtos\src\Service\PedidoService;
use App\Produtos\src\Service\ItemDePedidoService;
use App\Produtos\src\Service\ProdutoService;

class PedidoController extends Controller {

    private $pedidoService;

    public function __construct()
    {
       $this->pedidoService =  new PedidoService();
    }

    public function index()
    {
        try {
            $params = $this->getParams();
            $page = $params['page'] ?? 1;

            return  $this->view('Produtos\view\pedido\index', [
                'params' => $params,
                'pedidos'=> $this->pedidoService->paginate($params,$page),
                'total_items' => $this->pedidoService->getTotalItems($params),
                'limit'=> $this->pedidoService->getLimit(),
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }

    public function save()
    {
        try {
            $itemPedidoService =  new ItemDePedidoService();
            $produtoService =  new ProdutoService();

            $params = $this->getParams();
            $form = $this->getForm();
            $id = $params['id'] ?? null;
           
            if($this->isItPost()) {
                $id = $this->pedidoService->save($form);
                $this->redirect("/pedidos/save?id={$id}&success=1");
            }

            return  $this->view('Produtos\view\pedido\save', [
                'pedido' => $id > 0 ? $this->pedidoService->getItemById($id) : [],
                'params'=> $params,
                'itens' => $id > 0 ? $itemPedidoService->getItemsByPedidoId($id) : [],
                'produtos'=> $id > 0 ? $produtoService->getProdutosDisponiveisNoPedido($id) : [],
                'valor_total_pedido'=> $id > 0 ? $itemPedidoService->getValorTotalDoPedido($id) : 0,
            ]);
        } catch (\Exception $exception) {
            return  $this->view('Produtos\view\pedido\save', [
                'pedido' => $form,
                'error' => $exception->getMessage(),
                'itens' => $id > 0 ? $itemPedidoService->getItemsByPedidoId($id) : [],
                'produtos'=> $id > 0 ? $produtoService->getProdutosDisponiveisNoPedido($id) : [],
                'valor_total_pedido'=> $id > 0 ? $itemPedidoService->getValorTotalDoPedido($id) : 0,
            ]);
            throw $exception;
        }    
    }

    public function delete()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $this->pedidoService->deleteById($id);

            $this->redirect('/pedidos?success=1');
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }


    public function finalizar()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $this->pedidoService->finalizarPedido($id);

            $this->redirect('/pedidos?success=1');
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }
}