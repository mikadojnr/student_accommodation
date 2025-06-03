<?php

namespace App\Core;

/**
 * Database class
 * Singleton pattern for database connection
 */
class Database
{
    /**
     * @var Database
     */
    private static $instance = null;

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var array
     */
    private $config;

    /**
     * Private constructor to prevent direct instantiation
     *
     * @param array $config
     */
    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Get the database instance
     *
     * @param array $config
     * @return Database
     */
    public static function getInstance(?array $config = null)
    {
        if (self::$instance === null) {
            if ($config === null) {
                throw new \InvalidArgumentException('Database configuration is required');
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Connect to the database
     *
     * @return void
     */
    private function connect()
    {
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
        
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']} COLLATE {$this->config['collation']}"
        ];

        try {
            $this->connection = new \PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $options
            );
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Get the PDO connection
     *
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Execute a query
     *
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Begin a transaction
     *
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit a transaction
     *
     * @return bool
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rollback a transaction
     *
     * @return bool
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }

    /**
     * Get the last inserted ID
     *
     * @return string
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
