<?php 
use Database\DB;

require __DIR__.'/../../vendor/autoload.php';

DB::query(file_get_contents("../migrations/Produtos.sql"));
echo "Migration Create Produto Executado!\n";


DB::query(file_get_contents("../migrations/ArquivosDeProduto.sql"));
echo "Migration Create Arquivos de Produto Executado!\n";

