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
        //var_dump($_GET);
        return  $this->view('Produtos\view\produto\index', ['users' => 'testes']);
    }
}