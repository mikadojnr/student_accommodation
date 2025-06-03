<?php

namespace App\Core;

use App\Core\Database;

abstract class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    abstract public function up();
    abstract public function down();

    protected function execute($sql)
    {
        return $this->db->exec($sql);
    }

    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
