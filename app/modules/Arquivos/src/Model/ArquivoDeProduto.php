<?php

namespace App\Arquivos\src\Model;

use App\Core\src\Model\Model;

class ArquivoDeProduto extends Model {
    protected $table = 'arquivos_de_produtos';
    
    public function normalizeDataSource(): array
    {
        return [
           
        ];
    }
}