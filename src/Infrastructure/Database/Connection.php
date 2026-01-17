<?php

namespace Infrastructure\Database;

use PDO;
use PDOException;

require_once __DIR__ . '/../Config/config.php';

class Connection
{
        private string $host;
        private string $db;
        private string $user;
        private string $pass;
        private string $charset;
        public $pdo;

        public function __construct()
        {
                $this->host = $_ENV['DB_HOST'];
                $this->db = $_ENV['DB_NAME'];
                $this->user = $_ENV['DB_USER'];
                $this->pass = $_ENV['DB_PASS'];
                $this->charset = $_ENV['DB_CHARSET'];
        }

        public function connect()
        {
                try {
                        $connection = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
                        $options = [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_EMULATE_PREPARES => false,
                        ];

                        $this->pdo = new PDO($connection, $this->user, $this->pass, $options);

                        return $this->pdo;
                } catch (PDOException $e) {
                        error_log('Error de conexión: ' . $e->getMessage());
                        throw new \Exception('Error de conexión a la base de datos');
                }
        }
}

?>