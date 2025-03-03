<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="src/style/login_style.css">
    <link rel="shortcut icon" type="image/x-icon" href="src/images/cobra.svg" /> 
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Login Form -->
            <form action="login.php" method="POST" class="login-form">
                <h2>Login</h2>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <div class="form-switch">
                    <button type="button" class="switch-button" onclick="toggleForms()">Need an account? Register</button>
                </div>
            </form>

            <!-- Register Form -->
            <form action="register.php" method="POST" class="register-form">
                <h2>Register</h2>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Register</button>
                <div class="form-switch">
                    <button type="button" class="switch-button" onclick="toggleForms()">Already have an account? Login</button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($_SESSION['register_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['register_success']; 
            unset($_SESSION['register_success']); // Clear the message after displaying
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <script src="src/script/login.js"></script>
</body>
</html>