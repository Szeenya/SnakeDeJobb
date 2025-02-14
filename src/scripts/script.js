function switchToLogin() {
    window.history.pushState({}, '', '/login');
    document.getElementById('register-container').style.display = 'none';
    document.getElementById('login-container').style.display = 'block';
    document.title = 'Bejelentkezés';
}

function switchToRegister() {
    window.history.pushState({}, '', '/register');
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('register-container').style.display = 'block';
    document.title = 'Regisztráció';
}

function showGame() {
    document.getElementById('register-container').style.display = 'none';
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('game-container').style.display = 'block';
}

document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('/register', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Registration successful!') {
            switchToLogin(); // Redirect to login page
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('/login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Sikeres bejelentkezés') {
            showGame(); // Játék megnyitása
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});


