<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Get user's rank from database
require 'db.php';
$stmt = $db->prepare("
    SELECT username FROM users 
    WHERE personal_best > (SELECT personal_best FROM users WHERE username = ?) 
    LIMIT 1
");
$stmt->execute([$_SESSION['username']]);
$hasHigherScore = $stmt->fetch();
$isFirstPlace = !$hasHigherScore;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game</title>
    <link rel="shortcut icon" type="image/x-icon" href="src/images/icon.svg" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/style/game_style.css">
    <link rel="stylesheet" href="src/style/darkmode_style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-transparent">
  <div class="container-fluid">
  <span class="navbar-brand welcome-text">Welcome, 
    <span class="username <?php echo $isFirstPlace ? 'first-place' : ''; ?>">
      <?php echo htmlspecialchars($_SESSION['username']); ?>
    </span> !
  </span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Game Options
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="MapIncrease()">Increase Map Size</a></li>
            <li><a class="dropdown-item" href="#" onclick="MapDecrease()">Decrease Map Size</a></li>
            <li><a class="dropdown-item" href="#" onclick="resetGame()">Reset Game</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#leaderboardModal">Leaderboard</a>
        </li>
      </ul>
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModalLabel">User Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="settingsForm" action="update_settings.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
          </div>
          <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" disabled>
      </div>
            <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword">
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
          </div>
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password (required)</label>
            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <label class="form-check-label" for="darkMode_toggle">Dark Mode </label>
                <input class="form-check-input" type="checkbox" role="switch" id="darkMode_toggle" name="darkMode">
            </div>
          </div>
        </form>
        <hr class="border-light mt-4">
      
          <button type="button" class="btn btn-danger btn-delete-account" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="bi bi-trash-fill me-2"></i>Delete Account
          </button>
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="settingsForm" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <form id="deleteAccountForm" action="delete_account.php" method="POST">
          <div class="mb-3">
            <label for="deleteConfirmPassword" class="form-label">Enter your password to confirm:</label>
            <input type="password" class="form-control" id="deleteConfirmPassword" name="password" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="deleteAccountForm" class="btn btn-danger">Delete Account</button>
      </div>
    </div>
  </div>
</div>

<!-- Leaderboard Modal -->
<div class="modal fade" id="leaderboardModal" tabindex="-1" aria-labelledby="leaderboardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaderboardModalLabel">Leaderboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Player</th>
                            <th scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboardTable">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="page-container">
        <h1>Snake Game</h1>
        
        <div class="game-wrapper">
            <div class="game_container">
                <canvas width="400" height="400"></canvas>
                
                <div class="controls">
                    <button id="startButton" class="btn btn-success">Start Game</button>
                    <button id="resetButton" class="btn btn-danger" style="display: none;">Reset Game</button>
                </div>
            </div>

            <div class="stats-panel">
                <p id="score">Score: 0</p>
                <div id="lives"></div>
                <div id="shields"></div>
            </div>
        </div>
    </div>

    <script src="src/script/snake_logic.js"></script>
    <script src="src/script/ui_controls.js"></script>
    <script src="src/script/darkmode.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>