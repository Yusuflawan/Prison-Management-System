<?php

class  Database {
    private $host = "localhost";
    private $database = "prison";
    private $username = "root";
    private $password = "";
    private $conn;

    // DB connection
    public function connect(){     
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        // error handler
        if ($this->conn->connect_error) {
            die("connection failed" . $this->conn->connect_error);
        }
        return $this->conn;
    }

}


?>