<?php

namespace App\Arquivos\src\Controller;

use App\Core\src\Controller\Controller;
use App\Arquivos\src\Service\ArquivoDeProdutoService;
use App\Produtos\src\Service\ProdutoService;
use Exception;

class ArquivoDeProdutoController extends Controller {

    private $arquivoDeProdutoService;

    public function __construct()
    {
       $this->arquivoDeProdutoService =  new ArquivoDeProdutoService();
    }

    public function save()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $produtoService = new ProdutoService();

            if(!$id) {
                throw new Exception("Informe o id do produto!");
            }

            return  $this->view('Arquivos\view\arquivo-de-produto\save', [
                'produto' => $produtoService->getItemById($id),
            ]);
          
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }    
    }

    public function upload()
    {
        try {
            $params = $this->getParams();
            $id = $params['id'] ?? null;
            $this->arquivoDeProdutoService->uploadFile($id);
          
            $this->doNotRender();
        } catch (\Exception $exception) {
            $this->getJsonErrors($exception->getMessage());
        }    
    }
}