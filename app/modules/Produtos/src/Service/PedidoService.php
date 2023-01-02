<?php

namespace App\Produtos\src\Service;

use App\Core\src\Service\Service;
use App\Produtos\src\Service\ItemDePedidoService;
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
            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }
           
            $this->deletarItensDoPedido($id);
            $result = $this->delete($id);
            
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
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at, p.pedido_finalizado,
            ({$this->subqueryValorTotalDoPedido()}) as valor_total_pedido
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

    public function finalizarPedido($id): int
    {
        try {
            DB::beginTransaction();

            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }

            $result = $this->update($id,['pedido_finalizado' => 1]);
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }     
    }

    private function getWhereClause(array $params = [])
    {
        $id = $params['id'] ?? null;
        $descricao = $params['descricao_pedido'] ?? null;
        $pedidoFinalizado = $params['pedido_finalizado'] ?? null;

        $where = "";
        if($id) {
            $where = $this->addWhere($where,  "p.id = {$id}");
        }

        if($descricao) {
            $where = $this->addWhere($where,  "p.descricao_pedido LIKE '%{$descricao}%'");
        }

        if($pedidoFinalizado !== null && $pedidoFinalizado !== "") {
            $where = $this->addWhere($where,  "p.pedido_finalizado = {$pedidoFinalizado}");
        }

        return $where;
    }

    private function subqueryValorTotalDoPedido($alias = 'p'): string
    {
        return "SELECT FORMAT(SUM(ii.quantidade * ii.valor), 2, 'de_DE') as valor_total_pedido FROM itens_de_pedidos as ii
            WHERE ii.pedido_id = {$alias}.id
            LIMIT 1";
    }

    private function deletarItensDoPedido(int $id): void
    {
        $itemDePedidoService = new ItemDePedidoService();
        $itensDoPedido = $itemDePedidoService->getItemsByPedidoId($id);
        foreach($itensDoPedido as $item) {
            $itemDePedidoService->deleteById($item['id']);
        }
    }
}