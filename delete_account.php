<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    $username = $_SESSION['username'];
    $password = $_POST['password'];

    try {
        // Verify password and get user_id
        $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, md5($password)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Password verified, delete account
            $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
            if ($stmt->execute([$user['user_id']])) {
                // Clear session and redirect to login with success message
                session_destroy();
                header("Location: index.php?message=account_deleted");
                exit();
            }
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: Game.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting account: " . $e->getMessage();
        header("Location: Game.php");
        exit();
    }
}

header("Location: Game.php");
exit();