<?php
session_start();

// Ellenőrizzük, hogy a kért URL a /login végponton van-e
if ($_SERVER['REQUEST_URI'] !== '/login') {
    // Ha nem, irányítsuk át a főoldalra vagy egy hibaoldalra
    header('Location: /'); // Irányítsd át a főoldalra
    exit();
}

$servername = "localhost";
$username = "root"; // Az adatbázis felhasználóneve
$password = ""; // Az adatbázis jelszava
$dbname = "login_system";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizd a kapcsolatot
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Bejelentkezési adatok ellenőrzése
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$input_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Jelszó ellenőrzése
        if (password_verify($input_password, $row['password'])) {
            // Sikeres bejelentkezés
            $_SESSION['username'] = $input_username;
            // Irányítsd át a game.php oldalra
            header('Location: /game.php');
            exit();
        } else {
            echo "Hibás jelszó.";
        }
    } else {
        echo "Nincs ilyen felhasználó.";
    }
}

$conn->close();
?>