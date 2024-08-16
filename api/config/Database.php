<?php
class Database {
    private $host = 'pg-18ec6460-nightxcros-0caa.l.aivencloud.com';
    private $db_name = 'defaultdb';
    private $username = 'avnadmin';
    private $password = 'AVNS_5ixnsNl6FBOA-u2viVY';
    private $port = '14682';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->db_name",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
