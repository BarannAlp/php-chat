<?php
class Database {
    private $db;
 
    public function __construct() {
        $this->connect();
    }
 
    private function connect() {
        $dsn = 'sqlite:../config/database.db'; 
        $this->db = new PDO($dsn);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
 
    public function getConnection() {
        return $this->db;
    }
 }
 