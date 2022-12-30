<?php

namespace App\Core\src\Model;

abstract class Model {

    protected $table;
    protected $errors = [];

    
    abstract public function normalizeDataSource(): array;

    public function getTableName() : string
    {
            return $this->table;
    }

    protected static function getMessageRequired()
    {
            return "O Valor do campo é obrigatório!";
    }

    protected static function getMessageMinValue($min)
    {
        return "O Valor do campo precisa ser maior que {$min}!";
    }

    public function getErrors(): string
    {
        $errors = "";

        foreach($this->errors as $index => $value) {
            $errors .= "{$index}: {$value} <br>";
        }

        return $errors;
    }
}