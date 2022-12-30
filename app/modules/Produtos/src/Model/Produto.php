<?php

namespace App\Produtos\src\Model;

use App\Core\src\Model\Model;

class Produto extends Model {
    protected $table = 'produtos';
    
    protected $id;
    protected $descricao;
    protected $valor;
    protected $estoque;

    public function setId(int $id): void  
    {
        $this->id = $id;
    }

    public function getId(): int  
    {
        return $this->id;
    }

    public function setDescricao($descricao): void  
    {
        if(!$descricao) {
            $this->errors['Descrição'] = parent::getMessageRequired();
        }

        $this->descricao = $descricao;
    }

    public function getDescricao(): string  
    {
        return $this->descricao;
    }

    public function setValor($valor): void  
    {
        if(!$valor || $valor < 0) {
            $this->errors['Valor'] = parent::getMessageMinValue(0);
        }
        $this->valor = $valor;
    }

    public function getValor(): float  
    {
        return $this->valor;
    }

    public function setEstoque($estoque): void  
    {
        if(!$estoque || $estoque < 0) {
            $this->errors['Estoque'] = parent::getMessageMinValue(0);
        }
        $this->estoque = $estoque;
    }

    public function getEstoque(): int  
    {
        return $this->estoque;
    }

    public function normalizeDataSource(): array
    {
        return [
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'estoque' => $this->estoque,
        ];
    }
}