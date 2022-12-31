<?php

namespace App\Produtos\src\Model;

use App\Core\src\Model\Model;
use App\Core\helper\Cpf;

class Pedido extends Model {
    protected $table = 'pedidos';
    
    protected $id;
    protected $descricao_pedido;
    protected $nome_comprador;
    protected $cpf_comprador;

    public function setId(int $id): void  
    {
        $this->id = $id;
    }

    public function getId(): int  
    {
        return $this->id;
    }

    public function setDescricaoPedido($value): void  
    {
        if(!$value) {
            $this->errors['Descrição'] = parent::getMessageRequired();
        }

        $this->descricao_pedido = $value;
    }

    public function getDescricaoPedido(): string  
    {
        return $this->descricao_pedido;
    }

    public function setNomeComprador($value): void  
    {
        $this->nome_comprador = $value;
    }

    public function getNomeComprador(): string  
    {
        return $this->nome_comprador;
    }

    public function setCpfComprador($value): void  
    {
        if(Cpf::validateCpf($value) == false) {
            $this->errors['CPF Comprador'] = parent::getMessageInvalidCpf();
        }
        $this->cpf_comprador = $value;
    }

    public function getCpfComprador(): string  
    {
        return $this->cpf_comprador;
    }

    public function normalizeDataSource(): array
    {
        return [
            'descricao_pedido' => $this->descricao_pedido,
            'nome_comprador' => $this->nome_comprador,
            'cpf_comprador' => $this->cpf_comprador,
        ];
    }

}
