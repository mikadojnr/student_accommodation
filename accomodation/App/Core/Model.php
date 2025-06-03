<?php

namespace App\Core;

/**
 * Model class
 * Base class for all models
 */
abstract class Model
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return static|null
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return null;
        }
        
        return $this->hydrate($result);
    }

    /**
     * Get all records
     *
     * @return array
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = $this->hydrate($result);
        }
        
        return $models;
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        
        $columns = implode(', ', array_keys($fillableData));
        $placeholders = implode(', ', array_fill(0, count($fillableData), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($fillableData));
        
        $id = $this->db->lastInsertId();
        
        return $this->find($id);
    }

    /**
     * Update a record
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }
        
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        
        $setClause = implode(' = ?, ', array_keys($fillableData)) . ' = ?';
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?");
        $values = array_merge(array_values($fillableData), [$this->attributes[$this->primaryKey]]);
        
        return $stmt->execute($values);
    }

    /**
     * Delete a record
     *
     * @return bool
     */
    public function delete()
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        
        return $stmt->execute([$this->attributes[$this->primaryKey]]);
    }

    /**
     * Where clause
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return array
     */
    public function where($column, $operator, $value = null)
    {
        // If only 2 parameters are provided, assume '=' operator
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} {$operator} ?");
        $stmt->execute([$value]);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = $this->hydrate($result);
        }
        
        return $models;
    }

    /**
     * First where clause
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return static|null
     */
    public function firstWhere($column, $operator, $value = null)
    {
        $results = $this->where($column, $operator, $value);
        
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Hydrate the model with attributes
     *
     * @param array $attributes
     * @return static
     */
    protected function hydrate(array $attributes)
    {
        $model = new static();
        $model->attributes = $attributes;
        
        return $model;
    }

    /**
     * Get an attribute
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set an attribute
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Check if an attribute exists
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }
}
