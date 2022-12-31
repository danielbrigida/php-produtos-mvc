<?php

namespace App\Produtos\src\Controller;

use App\Core\src\Controller\Controller;
use App\Produtos\src\Service\ProdutoService;
use App\Arquivos\src\Service\ArquivoDeProdutoService;

class ProdutoController extends Controller {

    private $produtoService;

    public function __construct()
    {
       $this->produtoService =  new ProdutoService();
    }

    public function index()
    {
        try {
            $params = $this->getParams();
            $page = $params['page'] ?? 1;

            return  $this->view('Produtos\view\produto\index', [
                'params' => $params,
                'produtos'=> $this->produtoService->paginate($params,$page),
                'total_items' => $this->produtoService->getTotalItems($params),
                'limit'=> $this->produtoService->getLimit(),
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }

    public function save()
    {
        try {
            $arquivoDeProdutoService = new ArquivoDeProdutoService();
            $params = $this->getParams();
            $form = $this->getForm();

            $id = $params['id'] ?? null;
           
            if($this->isItPost()) {
                $id = $this->produtoService->save($form);
                $this->redirect("/produtos/save?id={$id}&success=1");
            }

            return  $this->view('Produtos\view\produto\save', [
                'produto' => $id > 0 ? $this->produtoService->getItemById($id) : [],
                'params'=> $params,
                'arquivos'=> $id > 0 ? $arquivoDeProdutoService->getItemsByProdutoId($id) : [],
            ]);
        } catch (\Exception $exception) {
            return  $this->view('Produtos\view\produto\save', [
                'produto' => $form,
                'error' => $exception->getMessage(),
            ]);
            throw $exception;
        }    
    }

    public function delete()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $this->produtoService->deleteById($id);

            $this->redirect('/produtos?success=1');
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }
}