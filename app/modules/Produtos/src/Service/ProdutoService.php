<?php

namespace App\Produtos\src\Service;

use App\Arquivos\src\Service\ArquivoDeProdutoService;
use App\Core\src\Service\Service;
use App\Produtos\src\Model\Produto;
use Exception;
use Database\DB;

class ProdutoService extends Service {

    private $produtoModel;

    public function __construct()
    {
       $this->produtoModel =  new Produto();
       $this->table = $this->produtoModel->getTableName();
    }
    
    public function save(array $data = [])
    {
        try {
            DB::beginTransaction();
            $id = $data['id'] ?? null;
            $this->produtoModel->setDescricao($data['descricao'] ?? null);
            $this->produtoModel->setValor($data['valor'] ?? null);
            $this->produtoModel->setEstoque($data['estoque'] ?? null);

            if($this->produtoModel->getErrors()) {
                throw new Exception($this->produtoModel->getErrors());
            }

            $id = $id && $id > 0 ? $this->update($id, $this->produtoModel->normalizeDataSource())  
                : $this->create($this->produtoModel->normalizeDataSource());

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
            $arquivoDeProdutoService= new ArquivoDeProdutoService();

            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }
            $arquivos = $arquivoDeProdutoService->getItemsByProdutoId($id);
            foreach($arquivos as $arquivo) {
                $arquivoDeProdutoService->deleteById($arquivo['id']);
            }

            $result = $this->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }     
    }

    public function getAll($data = []): array
    {
        return $this->fetchAll("
            SELECT p.id, p.descricao, p.estoque, p.valor FROM {$this->table} as p
            ORDER BY p.descricao ASC
        ;");
    }

    public function paginate(array $params = [], int $page): array
    {
        return $this->fetchAll("
            SELECT p.id, p.descricao, p.estoque, FORMAT(p.valor, 2, 'de_DE') as valor ,
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at,
            FROM {$this->table} as p
            {$this->getWhereClause($params)}
            ORDER BY p.descricao ASC
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
            ORDER BY p.descricao ASC
        ;");

        return $data['count'];
    }

    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT p.id, p.descricao, p.estoque, p.valor,  FORMAT(p.valor, 2, 'de_DE') as valor_formatado FROM {$this->table} as p
            WHERE :id=id
        ;", ['id' => $id]);
    }

    public function getProdutosDisponiveisNoPedido($pedidoId)
    {

        return $this->fetchAll("
            SELECT p.id, p.descricao, p.estoque, FORMAT(p.valor, 2, 'de_DE') as valor ,
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at 
            FROM {$this->table} as p
            WHERE NOT EXISTS(
                SELECT 1 FROM itens_de_pedidos AS i
                WHERE i.produto_id= p.id
                AND i.pedido_id = {$pedidoId}
                LIMIT 1
            )
            ORDER BY p.id ASC
        ;");

    }

    public function updateEstoque(int $id, int $estoque): bool
    {
        try {
            $data = $this->getItemById($id);

            $this->produtoModel->setDescricao($data['descricao'] ?? null);
            $this->produtoModel->setValor(($data['valor'] ?? null), false);
            $this->produtoModel->setEstoque($estoque ?? null);

            if($this->produtoModel->getErrors()) {
                throw new Exception($this->produtoModel->getErrors());
            }

            $this->update($id, $this->produtoModel->normalizeDataSource());

            return true;
        } catch (\Exception $exception) {
            throw $exception;
        }       
    }

    private function getWhereClause(array $params = [])
    {
        $id = $params['id'] ?? null;
        $descricao = $params['descricao'] ?? null;

        $where = "";
        if($id) {
            $where = $this->addWhere($where,  "p.id = {$id}");
        }
        if($descricao) {
            $where = $this->addWhere($where,  "p.descricao LIKE '%{$descricao}%'");
        }

        return $where;
    }
}