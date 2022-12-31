<?php

namespace App\Produtos\src\Service;

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

            $id && $id > 0 ? $this->update($id, $this->produtoModel->normalizeDataSource())  
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
            if(!$id) {
                throw new Exception("É necessário informar um id!");
            }

            return $this->delete($id);
        } catch (\Exception $exception) {
            throw $exception;
        }     
    }

    public function getAll($data = []): array
    {
        return $this->fetchAll("
            SELECT p.id, p.descricao, p.estoque, p.valor FROM produtos as p
            ORDER BY p.descricao ASC
        ;");
    }

    public function paginate(array $params = [], int $page): array
    {
        return $this->fetchAll("
            SELECT p.id, p.descricao, p.estoque, FORMAT(p.valor, 2, 'de_DE') as valor ,
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at 
            FROM produtos as p
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
            SELECT count(*) as count FROM produtos as p
            {$this->getWhereClause($params)}
            ORDER BY p.descricao ASC
        ;");

        return $data['count'];
    }

    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT p.id, p.descricao, p.estoque, p.valor,  FORMAT(p.valor, 2, 'de_DE') as valor_formatado FROM produtos as p
            WHERE :id=id
        ;",['id' => $id]);
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