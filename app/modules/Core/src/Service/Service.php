<?php 

namespace  App\Core\src\Service;

use Database\DB;

abstract class Service { 

    protected $table;

    const DEFAULT_LIMIT = 20;

    protected function create(array $data = [], bool $createdAt = true): float 
    {
        if($createdAt) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }    
        return DB::insert($this->table, $data);
    }

    protected function update(int $id, array $data = [], $updatedAt = true): float 
    {
        if($updatedAt) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }  

        DB::update($this->table, $data, ['id' => $id]);

        return $id;
    }

    protected function fetchAll(string $select, array $bind = []): array
    {
        return DB::select($select, $bind);
    }

    protected function fetchOne(string $select, array $bind = []): array 
    {
        return DB::selectOne($select, $bind);
    }

    protected function delete(int $id): bool
    {
        return DB::deleteById($this->table, $id);
    }

    protected function addWhere(string $where,string $clause): string
    {
        return !$where ? " WHERE " . $clause :
             $where." AND " .$clause;

    }

    protected function getDefaultLimit(): int
    {
        return self::DEFAULT_LIMIT;
    }

    protected function getOffset(int $page)
    {
        return ($page - 1) * $this->getDefaultLimit();

    }
}