<?php

namespace App\Produtos\src\Service;

use App\Core\src\Service\Service;
use App\Produtos\src\Model\ItemDePedido;
use App\Produtos\src\Service\ProdutoService;
use Exception;
use Database\DB;

class ItemDePedidoService extends Service {

    private $itemDePedidoModel;

    public function __construct()
    {
       $this->itemDePedidoModel =  new ItemDePedido();
       $this->table = $this->itemDePedidoModel->getTableName();
    }
    
    public function createItem(array $data = [])
    {
        try {
            DB::beginTransaction();
            $produtoService = new ProdutoService();
            $produtoId = $data['produto_id'] ?? 0; 
            $produto = $produtoService->getItemById($produtoId);

          
            $this->itemDePedidoModel->setProdutoId($produtoId);
            $this->itemDePedidoModel->setPedidoId($data['pedido_id'] ?? null);
            $this->itemDePedidoModel->setValor($produto['valor'] ?? null);
            $this->itemDePedidoModel->setQuantidade($data['quantidade'] ?? null);

            if($this->itemDePedidoModel->getErrors()) {
                throw new Exception($this->itemDePedidoModel->getErrors());
            }

            $this->atualizarEstoqueNoCadastro([
                'produto' => $produto,
                'quantidade' => $data['quantidade'] ?? null,
            ]);
            $id = $this->create($this->itemDePedidoModel->normalizeDataSource());

            DB::commit();
            
            return $id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }

    public function updateItem(array $data = [],int $id)
    {
        try {
            DB::beginTransaction();
            $produtoService = new ProdutoService();
            $produtoId = $data['produto_id'] ?? 0; 
            $produto = $produtoService->getItemById($produtoId);

            $this->itemDePedidoModel->setProdutoId($produtoId);
            $this->itemDePedidoModel->setPedidoId($data['pedido_id'] ?? null);
            $this->itemDePedidoModel->setQuantidade($data['quantidade'] ?? null);

            if($this->itemDePedidoModel->getErrors()) {
                throw new Exception($this->itemDePedidoModel->getErrors());
            }

            $this->atualizarEstoqueAoEditar([
                'produto' => $produto,
                'quantidade' => $data['quantidade'] ?? null,
                'id' => $id,
            ]);
            $id = $this->update($id, $this->itemDePedidoModel->normalizeDataSource(true));

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

            $this->atualizarEstoqueNaExclusao($id);
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
            SELECT i.id, i.produto_id, i.pedido_id, i.valor, i.quantidade FROM {$this->table} as i
            ORDER BY i.id ASC
        ;");
    }


    public function getItemById(int $id): array
    {
        $pedido = $this->fetchOne("SELECT i.id, i.produto_id, i.pedido_id, i.valor, i.quantidade,
            FORMAT(p.valor, 2, 'de_DE') as valor_formatado,
            p.descricao as 'nome_produto', p.estoque as 'estoque_produto' 
            FROM {$this->table} as i
            INNER JOIN produtos as p
            ON p.id = i.produto_id
            WHERE :id=i.id
        ;",['id' => $id]);

        $pedido['valor_total_pedido'] = $this->getValorTotalDoPedido($pedido['pedido_id']);    
        return $pedido;
    }


    public function getItemsByPedidoId(int $pedidoId): array
    {
        return $this->fetchAll("SELECT i.id, i.produto_id, i.pedido_id, i.valor, i.quantidade,
            FORMAT(p.valor, 2, 'de_DE') as valor_formatado,
            p.descricao as 'nome_produto', p.estoque as 'estoque_produto' 
            FROM {$this->table} as i
            INNER JOIN produtos as p
            ON p.id = i.produto_id
            WHERE :pedido_id=i.pedido_id
            ORDER BY i.id desc
        ;",['pedido_id' => $pedidoId]);
    }

    public function getValorTotalDoPedido($pedidoId)
    {
        $pedido =  $this->fetchOne("SELECT FORMAT(SUM(i.quantidade * i.valor), 2, 'de_DE') as valor_total_pedido 
            from {$this->table} as i
            WHERE pedido_id = {$pedidoId};");
        return $pedido['valor_total_pedido'] ?? 0;   
    }

    private function atualizarEstoqueNaExclusao(int $id): bool
    {
        $produtoService = new ProdutoService();
        $itemPedido = $this->getItemById($id);

        if(!$itemPedido) {
            throw new Exception("Item do pedido não encontrado!");
        }

        $produtoId =  $itemPedido['produto_id'] ?? null;
        $quantidade = (int) $itemPedido['quantidade'] ?? 0;
        $produto = $produtoService->getItemById($produtoId);

        if(!$produto) {
            throw new Exception("Produto não encontrado!");
        }

        $estoque = (int) $produto['estoque'] ?? 0;
        $estoqueAtualizado = $estoque + $quantidade;
        $produtoService->updateEstoque($produtoId, $estoqueAtualizado);

        return true;
    }

    private function atualizarEstoqueAoEditar(array $data): bool
    {
        $produtoService = new ProdutoService();
        $id = $data['id'] ?? [];
        $produto = $data['produto'] ?? [];
        $quantidadeNova = (int) $data['quantidade'] ?? 0;

        $itemPedido = $this->getItemById($id);
        $quantidadeAntiga = (int) $itemPedido['quantidade'] ?? 0;

        if(!$itemPedido) {
            throw new Exception("Item do pedido não encontrado!");
        }

        if(!$produto) {
            throw new Exception("Produto não encontrado!");
        }

        $estoqueAtualizado = $produto['estoque'] + ($quantidadeAntiga - $quantidadeNova);

        $this->validacoesDeQuantidadeDeItens([
            'estoqueAtualizado' => $estoqueAtualizado,
            'quantidade' => $quantidadeNova,
            'estoque' => $produto['estoque'],
        ]);
        $produtoService->updateEstoque($produto['id'], $estoqueAtualizado);

        return true;
    }

    private function atualizarEstoqueNoCadastro(array $data = []): bool
    {
        $produtoService = new ProdutoService();
        $produto = $data['produto'] ?? [];
        $quantidade = $data['quantidade'] ?? 0;
        $produtoId =  $produto['id'] ?? null;

        if(!$produto) {
            throw new Exception("Produto não encontrado!");
        }

        $estoque = (int) $produto['estoque'] ?? 0;
        $estoqueAtualizado = $estoque - $quantidade;
        $this->validacoesDeQuantidadeDeItens([
            'estoqueAtualizado' => $estoqueAtualizado,
            'quantidade' => $quantidade,
            'estoque' => $estoque,
        ]);
        $produtoService->updateEstoque($produtoId, $estoqueAtualizado);

        return true;
    }

    private function validacoesDeQuantidadeDeItens($data = []): bool
    {
        $estoqueAtualizado = $data['estoqueAtualizado'] ?? null;
        $estoque = $data['estoque'] ?? null;
        $quantidade = $data['quantidade'] ?? null;

        if($estoqueAtualizado < 0) {
            throw new Exception("A quantidade não pode ser maior que o estoque atual (estoque: {$estoque})!");
        }

        if($quantidade < 1) {
            throw new Exception("A quantidade não pode ser menor que 1!");
        }

        return true;
    }

}