<?php

use Database\DB;

ini_set('display_errors', 'On');

//require __DIR__."/config.php";
require __DIR__.'/vendor/autoload.php';

use App\Produtos\src\Service\ProdutoService;
use App\Produtos\src\Controller\ProdutoController;

/*echo "<pre>";
$update = [
    'name' => 'aaaaaaa',
    'cost' => 15.00];
DB::deleteById("shipping_options",3);

var_dump(DB::select("SELECT * FROM shipping_options")); die;*/


$produtoService = new ProdutoService();
$produtoService->save([
    'descricao' => "123SAD325D",
    'valor' =>  1,
    'estoque'=> 1,
]);
echo "<pre>"; print_r($produtoService->getItemById(4)); die;
/*$produtoService->save([
    'id' => 1,
    'descricao' => 'aaaa',
    'valor' =>  15.00,
    'estoque'=> 10,
]);*/

/*$render = new ProdutoController();
$render->index();*/

require __DIR__ .'\app\modules\Produtos\router\route.php';

