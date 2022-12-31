<?php

namespace App\Arquivos\src\Service;

use App\Core\src\Service\Service;
use App\Arquivos\src\Model\ArquivoDeProduto;
use Exception;
use Database\DB;

class ArquivoDeProdutoService extends Service {

    private $arquivoDeProdutoModel;

    public function __construct()
    {
       $this->arquivoDeProdutoModel =  new ArquivoDeProduto();
       $this->table = $this->arquivoDeProdutoModel->getTableName();
    }
}