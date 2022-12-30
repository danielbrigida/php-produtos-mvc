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

    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT p.id, p.descricao, p.estoque, p.valor FROM produtos as p
            WHERE :id=id
        ;",['id' => $id]);
    }
}