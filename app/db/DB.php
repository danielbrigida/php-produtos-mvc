<?php

namespace Database;

use Database\ConfigDataBase;

class DB {
    public static $db;

    public static function connect()
    {
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION 
        );

        $dsn = 'mysql:dbname=' . ConfigDataBase::DB_NAME . ';host=' . ConfigDataBase::DB_HOST;
        self::$db = new \PDO($dsn, ConfigDataBase::DB_USER, ConfigDataBase::DB_PASS, $options);
        
        self::query("SET NAMES UTF8");
    }

    public static function query(string $sql, $vars = [])
    {
        if(empty(self::$db)) { 
            self::connect();
        }
     
        $bind = $vars ?? [];
        
        try {
            $sth = self::$db->prepare($sql);
            $sth->execute($bind);
            return $sth;
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $error.=PHP_EOL.PHP_EOL."SQL Statement:".PHP_EOL.$sql;
            throw new \Exception($error);
        }
    }

    public static function select($sql, ...$vars) { 
        $res = DB::query($sql, ...$vars);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function selectOne($sql, ...$vars) {
        $res = DB::query($sql, ...$vars);
        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    public static function insert(string $table, array $vars): float
    {
        $fields = array_keys($vars);
        $sql = "INSERT INTO " . $table . " (" . implode(", ", $fields) . ") VALUES (:" . implode(", :", $fields) . ");";
        $res = db::query($sql, $vars);
        return $res->rowCount();
    }

    public static function update(string $table, array $vars, $where, array $whereBind = [])
    {
        $sql = "UPDATE " . $table . " SET ";
        $bind = [];
       
        foreach($vars AS $field=>$value)
		{
			$set[]=$field."=:update_".$field;
			$bind[":update_" . $field]=$vars[$field];
		}

		$sql.=implode(" , ", $set);

        if (is_array($where)) {
            foreach ($where as $key=>$val)
			{
				$conditions[]=$key." = :where_".$key;
                $bind[':where_'.$key]=$val;
			}

            $sql .= " WHERE " . implode(" AND ", $conditions) . ";";
        }
        elseif (is_string($where)) { 
            $sql .= " WHERE " . $where . ";";
        } else { 
            throw new \Exception("Unreadable where condition", 1);
        }

        $res = DB::query($sql, $bind);
        return $res->rowCount();

    }

    public static function fetchRow($res)
    {
        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    public static function fetchOne($res)
    {
        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    public static function countRows($res)
    {
        return $res->rowCount();
    }

    public static function numRows($res)
    {
        return $res->rowCount();
    }

    public static function deleteById(string $table , int $id): bool
    {
        if(!$id) {
            return false;
        }

        self::query("DELETE FROM {$table} WHERE :id=id;", ['id' => $id]);
        return true;
    }

    public static function beginTransaction()
    {
        if(empty(self::$db)) { 
            self::connect();
        }
        self::$db->beginTransaction();
    }

    public static function commit()
    {
        self::$db->commit();
    }

    public static function rollBack()
    {
        self::$db->rollBack();
    }
}
