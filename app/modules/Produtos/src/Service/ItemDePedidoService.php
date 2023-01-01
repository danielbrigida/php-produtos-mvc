<?php

namespace App\Produtos\src\Service;

use App\Core\src\Service\Service;
use App\Produtos\src\Model\ItemDePedido;
use Exception;
use Database\DB;

class ItemDePedidoService extends Service {

    private $itemDePedidoModel;

    public function __construct()
    {
       $this->itemDePedidoModel =  new ItemDePedido();
       $this->table = $this->itemDePedidoModel->getTableName();
    }
    
    public function save(array $data = [])
    {
        try {
            DB::beginTransaction();
            $id = $data['id'] ?? null;
            $this->itemDePedidoModel->setProdutoId($data['produto_id'] ?? null);
            $this->itemDePedidoModel->setPedidoId($data['pedido_id'] ?? null);
            $this->itemDePedidoModel->setValor($data['valor'] ?? null);
            $this->itemDePedidoModel->setQuantidade($data['quantidade'] ?? null);

            if($this->itemDePedidoModel->getErrors()) {
                throw new Exception($this->itemDePedidoModel->getErrors());
            }

            $id = $id && $id > 0 ? $this->update($id, $this->itemDePedidoModel->normalizeDataSource())  
                : $this->create($this->itemDePedidoModel->normalizeDataSource());

            DB::commit();
            
            return $id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }

    public function deleteById($id) : bool
    {
        try {
            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }

            $result = $this->delete($id);

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }     
    }

    public function getAll($data = []): array
    {
        return $this->fetchAll("
            SELECT i.id, i.produto_id, i.pedido_id, i.valor, i.quantidade FROM {$this->table} as i
            ORDER BY i.id ASC
        ;");
    }


    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT i.id, i.produto_id, i.pedido_id, i.valor, i.quantidade FROM {$this->table} as i
            WHERE :id=id
        ;",['id' => $id]);
    }

}