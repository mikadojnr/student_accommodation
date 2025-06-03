<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function find($id)
    {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($result) {
            foreach ($result as $key => $value) {
                $instance->$key = $value;
            }
            return $instance;
        }
        
        return null;
    }

    public static function all()
    {
        $instance = new static();
        $stmt = $instance->db->query("SELECT * FROM {$instance->table}");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function where($column, $operator, $value = null)
    {
        $instance = new static();
        
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table} WHERE $column $operator ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function create($data)
    {
        $instance = new static();
        
        // Filter data to only include fillable fields
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $instance->fillable)) {
                $filteredData[$key] = $value;
            }
        }
        
        $columns = implode(',', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO {$instance->table} ($columns) VALUES ($placeholders)";
        $stmt = $instance->db->prepare($sql);
        
        foreach ($filteredData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        if ($stmt->execute()) {
            return static::find($instance->db->lastInsertId());
        }
        
        return false;
    }

    public function save()
    {
        if (isset($this->{$this->primaryKey})) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    private function insert()
    {
        $data = [];
        foreach ($this->fillable as $field) {
            if (isset($this->$field)) {
                $data[$field] = $this->$field;
            }
        }
        
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        if ($stmt->execute()) {
            $this->{$this->primaryKey} = $this->db->lastInsertId();
            return true;
        }
        
        return false;
    }

    private function update()
    {
        $data = [];
        foreach ($this->fillable as $field) {
            if (isset($this->$field)) {
                $data[$field] = $this->$field;
            }
        }
        
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :{$this->primaryKey}";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":{$this->primaryKey}", $this->{$this->primaryKey});
        
        return $stmt->execute();
    }

    public function delete()
    {
        if (!isset($this->{$this->primaryKey})) {
            return false;
        }
        
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->{$this->primaryKey}]);
    }

    public static function query($sql, $params = [])
    {
        $instance = new static();
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
