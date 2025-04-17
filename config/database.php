<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "social_network";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql, $params = [], $types = "") {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error in query: " . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }

    public function fetchAll($sql, $params = [], $types = "") {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchSingle($sql, $params = [], $types = "") {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function execute($sql, $params = [], $types = "") {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->affected_rows;
    }

    public function getInsertId() {
        return $this->conn->insert_id;
    }

    public function close() {
        $this->conn->close();
    }
}
?>