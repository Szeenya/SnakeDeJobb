function toggleForms() {
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    
    if (registerForm.classList.contains('active')) {
        // Switch to Login
        registerForm.classList.remove('active');
        loginForm.classList.remove('inactive');
        document.title = "Login";
    } else {
        // Switch to Register
        registerForm.style.display = 'block'; // Show register form first
        setTimeout(() => {
            loginForm.classList.add('inactive');
            registerForm.classList.add('active');
            document.title = "Register";
        }, 50);
    }
}

// Form initialization
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    
    // Initial state
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
    
    loginForm.classList.remove('inactive');
    registerForm.classList.remove('active');
});