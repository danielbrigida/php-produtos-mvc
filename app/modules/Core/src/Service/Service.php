<?php 

namespace  App\Core\src\Service;

use Database\DB;

abstract class Service { 

    protected $table;

    public function create(array $data = [], bool $createdAt = true): float 
    {
        if($createdAt) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }    
        return DB::insert($this->table, $data);
    }

    public function update(int $id, array $data = [], $updatedAt = true): float 
    {
        if($updatedAt) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }  

        return DB::update($this->table, $data, ['id' => $id]);
    }

    public function fetchAll(string $select, array $bind = []): array
    {
        return DB::select($select, $bind);
    }

    public function fetchOne(string $select, array $bind = []): array 
    {
        return DB::selectOne($select, $bind);
    }

    public function delete(int $id): bool
    {
        return DB::deleteById($this->table, $id);
    }
}