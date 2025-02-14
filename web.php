<?php
require_once "Router.php";
require_once "Config.php"; 

$router = new Router();

$router->get('/', function($request, $response) {
    $response->setContent('Welcome to the homepage!');
    $response->send();
    return $response;
});

// Regisztrációs endpoint
$router->post("/register", function($request, $response) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (empty($username) || empty($email) || empty($password1) || empty($password2)) {
        echo json_encode(['message' => 'Please fill in all fields.']);
        return;
    }

    if ($password1 !== $password2) {
        echo json_encode(['message' => 'Passwords do not match.']);
        return;
    }

    $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

    $config = new Config();
    $connection = $config->getConnection();

    $checkSql = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $connection->prepare($checkSql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['message' => 'Username or email already taken.']);
        return;
    }

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Registration successful!']);
    } else {
        echo json_encode(['message' => 'Registration failed: ' . $stmt->error]);
    }

    $stmt->close();
    $connection->close();
});



$response = $router->handleRequest();