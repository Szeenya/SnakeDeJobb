<?php

class Config {
    private $dbCon;

    public function __construct() {
        $this->dbCon = mysqli_connect('localhost', 'root', 'root', 'snake_db');
        if (!$this->dbCon) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->dbCon;
    }

    public function close() {
        mysqli_close($this->dbCon);
        $this->dbCon = null;
    }
}