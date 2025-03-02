<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentUser = $_SESSION['username'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $currentPassword = $_POST['currentPassword'];

    // Verify current password
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$currentUser, md5($currentPassword)]);

    if ($stmt->rowCount() > 0) {
        // Password verified, proceed with updates
        $updates = [];
        $params = [];

        if (!empty($username) && $username !== $currentUser) {
            $updates[] = "username = ?";
            $params[] = $username;
        }

        if (!empty($email)) {
            $updates[] = "email = ?";
            $params[] = $email;
        }

        if (!empty($newPassword) && $newPassword === $confirmPassword) {
            $updates[] = "password = ?";
            $params[] = md5($newPassword);
        }

        if (!empty($updates)) {
            $params[] = $currentUser; // Add current username for WHERE clause
            $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE username = ?";
            $stmt = $db->prepare($sql);
            if ($stmt->execute($params)) {
                if (!empty($username) && $username !== $currentUser) {
                    $_SESSION['username'] = $username; // Update session if username changed
                }
                $_SESSION['message'] = "Settings updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update settings.";
            }
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect.";
    }
}

header("Location: Game.php");
exit();
?>