<?php

namespace App\Produtos\src\Model;

use App\Core\src\Model\Model;

class ItemDePedido extends Model {
    protected $table = 'itens_de_pedidos';
    
    protected $id;
    protected $produto_id;
    protected $pedido_id;
    protected $valor;
    protected $quantidade;

    public function setId(int $id): void  
    {
        $this->id = $id;
    }

    public function getId(): int  
    {
        return $this->id;
    }

    public function setProdutoId(int $produto_id): void  
    {
        if(!$produto_id) {
            $this->errors['Produto'] = parent::getMessageRequired();
        }

        $this->produto_id = $produto_id;
    }

    public function getProdutoId(): int  
    {
        return $this->produto_id;
    }

    public function setPedidoId(int $pedido_id): void  
    {
        if(!$pedido_id) {
            $this->errors['Pedido'] = parent::getMessageRequired();
        }

        $this->pedido_id = $pedido_id;
    }

    public function getPedidoId(): int  
    {
        return $this->pedido_id;
    }

    public function setValor($valor): void  
    {
        $valor = (float) str_replace(',','.',str_replace('.','',$valor));
        if(!$valor || $valor < 0.01) {
            $this->errors['Valor'] = parent::getMessageMinValue(0.01);
        }

        $this->valor = $valor;
    }

    public function getValor(): float  
    {
        return $this->valor;
    }

    public function setQuantidade($quantidade): void  
    {
        if($quantidade < 0) {
            $this->errors['Estoque'] = parent::getMessageMinValue(0);
        }
        $this->quantidade = $quantidade;
    }

    public function getQuantidade(): int  
    {
        return $this->quantidade;
    }

    public function normalizeDataSource(): array
    {
        return [
            'produto_id' => $this->produto_id,
            'pedido_id' => $this->pedido_id,
            'valor' => $this->valor,
            'quantidade' => $this->quantidade,
        ];
    }
}