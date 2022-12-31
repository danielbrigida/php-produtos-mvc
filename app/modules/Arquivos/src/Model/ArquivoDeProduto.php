<?php

namespace App\Arquivos\src\Model;

use App\Core\src\Model\Model;

class ArquivoDeProduto extends Model {
    protected $table = 'arquivos_de_produtos';

    protected $id;
    protected $produto_id;
    protected $path_file;
    protected $nome_unico;
    protected $nome_original;
    protected $hash;

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

    public function setPathFile($path_file): void  
    {
        $this->path_file = $path_file;
    }

    public function getPathFile(): string  
    {
        return $this->path_file;
    }

    public function setNomeUnico($nome_unico): void  
    {
        $this->nome_unico = $nome_unico;
    }

    public function getNomeUnico(): string  
    {
        return $this->nome_unico;
    }

    public function setNomeOriginal($nome_original): void  
    {
        $this->nome_original = $nome_original;
    }

    public function getNomeOriginal(): string  
    {
        return $this->nome_original;
    
    }

    public function setHash($hash): void  
    {
        $this->hash = $hash;
    }

    public function getHash(): string  
    {
        return $this->hash;
    
    }

    public function normalizeDataSource(): array
    {
        return [
            'produto_id' => $this->produto_id,
            'path_file' => $this->path_file,
            'nome_unico' => $this->nome_unico,
            'nome_original' => $this->nome_original,
            'hash' => $this->hash,
        ];
    }
}