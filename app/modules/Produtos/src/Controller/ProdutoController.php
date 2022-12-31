<?php

namespace App\Produtos\src\Controller;

use App\Core\src\Controller\Controller;
use App\Produtos\src\Service\ProdutoService;

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
            $params = $this->getParams();
            $form = $this->getForm();

            $id = $params['id'] ?? null;
           
            if($this->isItPost()) {
                $this->produtoService->save($form);
                $this->redirect("/produtos?success=1");
            }

            return  $this->view('Produtos\view\produto\save', [
                'produto' => $id > 0 ? $this->produtoService->getItemById($id) : [],
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