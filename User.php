<?php
require_once 'Config.php';

class User {
    private $id;
    private $username;
    private $email;
    private $password;

    public function __construct($id, $username, $email, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public static function registerUser($username, $email, $password) {
        $config = new Config();
        $connection = $config->getConnection();

        $checkSql = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = $connection->prepare($checkSql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Username or email already taken
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        $result = $stmt->execute();
        $stmt->close();
        $connection->close();

        return $result;
    }

    public static function loginUser($username, $password) {
        $config = new Config();
        $connection = $config->getConnection();

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return new User($user['id'], $user['username'], $user['email'], $user['password']);
            }
        }

        $stmt->close();
        $connection->close();

        return null; // Invalid username or password
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}
?>