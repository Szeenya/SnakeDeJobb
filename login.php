<?php
session_start();
$db = require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Check credentials and get email and admin status
        $stmt = $db->prepare("SELECT user_id, username, email, is_admin FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, md5($password)]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin']; // Store admin status in session
            header("Location: Game.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }
}
?>