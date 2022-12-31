<?php

namespace App\Produtos\src\Service;

use App\Core\src\Service\Service;
use App\Produtos\src\Model\Pedido;
use Exception;
use Database\DB;

class PedidoService extends Service {

    private $pedidoModel;

    public function __construct()
    {
       $this->pedidoModel =  new Pedido();
       $this->table = $this->pedidoModel->getTableName();
    }
    
    public function save(array $data = [])
    {
        try {
            DB::beginTransaction();
            $id = $data['id'] ?? null;
            $this->pedidoModel->setDescricaoPedido($data['descricao_pedido'] ?? null);
            $this->pedidoModel->setNomeComprador($data['nome_comprador'] ?? null);
            $this->pedidoModel->setCpfComprador($data['cpf_comprador'] ?? null);

            if($this->pedidoModel->getErrors()) {
                throw new Exception($this->pedidoModel->getErrors());
            }

            $id = $id && $id > 0 ? $this->update($id, $this->pedidoModel->normalizeDataSource())  
                : $this->create($this->pedidoModel->normalizeDataSource());

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
            DB::beginTransaction();

            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }

            $result = $this->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }     
    }


    public function paginate(array $params = [], int $page): array
    {
        return $this->fetchAll("
            SELECT p.id, p.descricao_pedido, p.nome_comprador, p.cpf_comprador, 
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at 
            FROM {$this->table} as p
            {$this->getWhereClause($params)}
            ORDER BY p.created_at DESC
            LIMIT {$this->getLimit()}
            OFFSET {$this->getOffset($page)}
        ;");
    }

    public function getLimit(): int
    {
        return $this->getDefaultLimit();
    }

    public function getTotalItems(array $params = []): int
    {
        $data = $this->fetchOne("
            SELECT count(*) as count FROM {$this->table} as p
            {$this->getWhereClause($params)}
            ORDER BY p.created_at DESC
        ;");

        return $data['count'];
    }

    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT p.id, p.descricao_pedido, p.nome_comprador, p.cpf_comprador FROM {$this->table} as p
            WHERE :id=id
        ;",['id' => $id]);
    }

    private function getWhereClause(array $params = [])
    {
        $id = $params['id'] ?? null;
        $descricao = $params['descricao_pedido'] ?? null;

        $where = "";
        if($id) {
            $where = $this->addWhere($where,  "p.id = {$id}");
        }
        if($descricao) {
            $where = $this->addWhere($where,  "p.descricao_pedido LIKE '%{$descricao}%'");
        }

        return $where;
    }
}