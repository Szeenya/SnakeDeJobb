<?php
session_start();
require 'db.php';

try {
    // Get top 10 personal best scores
    $stmt = $db->prepare("
        SELECT username, personal_best 
        FROM users 
        WHERE personal_best > 0
        ORDER BY personal_best DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $leaderboard]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}