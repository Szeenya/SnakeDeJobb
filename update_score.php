<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $score = intval($_POST['score']);
    $username = $_SESSION['username'];

    try {
        // Start transaction
        $db->beginTransaction();

        // Get user ID and current personal best
        $stmt = $db->prepare("SELECT user_id, personal_best FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // Insert score into scores table
            $stmt = $db->prepare("INSERT INTO scores (user_id, score) VALUES (?, ?)");
            $stmt->execute([$user['user_id'], $score]);
            $scoreId = $db->lastInsertId(); // Get the ID of the inserted score

            // Update personal_best if new score is higher
            if ($score > $user['personal_best']) {
                // Update personal_best in users table
                $stmt = $db->prepare("UPDATE users SET personal_best = ? WHERE user_id = ?");
                $stmt->execute([$score, $user['user_id']]);

                // Insert into leaderboard table
                $stmt = $db->prepare("INSERT INTO leaderboard (user_id, score_id) VALUES (?, ?)");
                $stmt->execute([$user['user_id'], $scoreId]);
            }

            // Commit transaction
            $db->commit();
            
            echo json_encode(['success' => true]);
        } else {
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => 'User not found']);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>