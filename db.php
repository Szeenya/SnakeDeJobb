<?php
$host = 'localhost';
$dbname = 'snake2_db';
$username = 'root';
$password = 'root'; // MAMP alapértelmezett jelszó

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;port=3306", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>