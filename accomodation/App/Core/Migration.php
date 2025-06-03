<?php

namespace App\Core;

/**
 * Migration class
 * Base class for database migrations
 */
abstract class Migration
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Run the migration
     */
    abstract public function up();

    /**
     * Reverse the migration
     */
    abstract public function down();

    /**
     * Execute a SQL query
     */
    protected function execute( string $sql)
    {
        $this->db->exec($sql);
    }

    
    /**
     * Check if a table exists
     *
     * @param string $table
     * @return bool
     */
    protected function tableExists($table)
    {
        $stmt = $this->db->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return $stmt->rowCount() > 0;
    }

    
}
