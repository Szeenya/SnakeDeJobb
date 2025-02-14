<?php require_once 'web.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/style/style.css">
</head>
<body>
    
    <!-- Regisztrációs div-->
    <div class="box" id="register-container">
        <h2>Regisztráció</h2>
        <form id="register-form" method="post" action="/register">
            <label for="name">Név</label>
            <input type="text" id="register-user" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="register-email" name="email" required>

            <label for="password1">Jelszó</label>
            <input type="password" id="register-pass1" name="password1" required>

            <label for="password2">Jelszó megerősítése</label>
            <input type="password" id="register-pass2" name="password2" required>

            <button type="submit"> Regisztrálj</button>
        </form>
        <button type="button" onclick="switchToLogin()">Már van fiókod? Jelentkezz be!</button>
    </div>

    <!-- Bejelentkezés div-->
    <div class="box" id="login-container" style="display:none;">
        <h2>Bejelentkezés</h2>
        <form action="login.php" method="post">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Bejelentkezés">
    </form>
        <button type="button" onclick="switchToRegister()">Nincs még fiókod? Regisztrálj!</button>
    </div>

    <!-- Játék div-->
    <div class="game_base" id="game-container" style="display:none;">
    <h1 class="mt-5">Snake Game</h1>
        <canvas id="gameCanvas" height="400px" width="400px" class="border"></canvas>
        <div class="mt-3">
            <button id="startButton" class="btn btn-success">Start Game</button>
            <button id="resetButton" class="btn btn-danger" style="display: none;">Reset Game</button>
            <button id="plus-100" onclick="MapIncrease()">+100</button>
            <button id="minus-100" onclick="MapDecrease()">-100</button>
        </div>
        <p id="score" class="mt-3">Score: 0</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="src/scripts/script.js"></script>
    <script src="src/scripts/snake_logic.js"></script>
</body>
</html>