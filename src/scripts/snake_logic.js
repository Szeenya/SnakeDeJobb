const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

let snake = [{ x: 10, y: 10 }];
let direction = { x: 0, y: 0 };
let food = { x: 15, y: 15, color: 'red', points: 1 };
let score = 0;
let gameInterval;
let mapSize = 400;

function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw grid
    ctx.strokeStyle = '#ccc'; // Grid color
    for (let x = 0; x <= canvas.width; x += 20) {
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, canvas.height);
        ctx.stroke();
    }
    for (let y = 0; y <= canvas.height; y += 20) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(canvas.width, y);
        ctx.stroke();
    }
    
    // Draw snake
    snake.forEach((segment, index) => {
        ctx.fillStyle = index === 0 ? 'lime' : 'green';
        ctx.fillRect(segment.x * 20, segment.y * 20, 18, 18); // Keep snake size constant
    });

    // Draw food
    ctx.fillStyle = food.color;
    ctx.fillRect(food.x * 20, food.y * 20, 18, 18); // Keep food size constant
}

function update() {
    const head = { x: snake[0].x + direction.x, y: snake[0].y + direction.y };

    // Check for collision with food
    if (head.x === food.x && head.y === food.y) {
        score += food.points;
        document.getElementById('score').innerText = `Score: ${score}`;
        snake.unshift(head);
        const eatenFoodColor = food.color;
        placeFood();

        // Increase speed for green or yellow apples
        if (eatenFoodColor === 'green' || eatenFoodColor === 'yellow') {
            clearInterval(gameInterval);
            gameInterval = setInterval(() => {
                update();
                draw();
            }, snakeSpeed / 2); // Double the speed

            setTimeout(() => {
                clearInterval(gameInterval);
                gameInterval = setInterval(() => {
                    update();
                    draw();
                }, snakeSpeed); // Reset to original speed after 5 seconds
            }, 5000);
        }
    } else {
        snake.unshift(head);
        snake.pop();
    }

    // Check for collision with walls or self
    if (head.x < 0 || head.x >= canvas.width / 20 || head.y < 0 || head.y >= canvas.height / 20 || collision(head)) {
        clearInterval(gameInterval);
        alert('Game Over! Your score: ' + score);
        resetGame();
    }
}

function collision(head) {
    return snake.slice(1).some(segment => segment.x === head.x && segment.y === head.y);
}

function placeFood() {
    food.x = Math.floor(Math.random() * (canvas.width / 20));
    food.y = Math.floor(Math.random() * (canvas.height / 20));
    

    // Különböző színű almák amit majd fel tudunk túrbózni

    const random = Math.random();
    if (random < 0.33) {
        food.color = 'red';
        food.points = 1;
    } else if (random < 0.66) {
        food.color = 'green';
        food.points = 5;
    } else {
        food.color = 'yellow';
        food.points = 10;
    }
}

function changeDirection(event) {
    switch (event.key) {
        case 'ArrowUp':
        case 'w':
        case 'W':
            if (direction.y === 0) direction = { x: 0, y: -1 };
            break;
        case 'ArrowDown':
        case 's':
        case 'S':
            if (direction.y === 0) direction = { x: 0, y: 1 };
            break;
        case 'ArrowLeft':
        case 'a':
        case 'A':
            if (direction.x === 0) direction = { x: -1, y: 0 };
            break;
        case 'ArrowRight':
        case 'd':
        case 'D':
            if (direction.x === 0) direction = { x: 1, y: 0 };
            break;
    }
}
var snakeSpeed=200;
function startGame() {
    snake = [{ x: 10, y: 10 }];
    direction = { x: 0, y: 0 };
    score = 0;
    document.getElementById('score').innerText = `Score: ${score}`;
    placeFood();
    gameInterval = setInterval(() => {
        update();
        draw();
    }, snakeSpeed); // Speed of the game (200 = 0.2 seconds)
}

function resetGame() {
    clearInterval(gameInterval);
    document.getElementById('resetButton').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
}

document.getElementById('startButton').addEventListener('click', () => {
    startGame();
    document.getElementById('startButton').style.display = 'none';
    document.getElementById('resetButton').style.display = 'block';
});

document.getElementById('resetButton').addEventListener('click', resetGame);

document.addEventListener('keydown', changeDirection);

// Increase map size
function MapIncrease() {
    if (mapSize < 800) { // Set an upper limit for the map size
        mapSize += 100;
        canvas.width = mapSize;
        canvas.height = mapSize;
    }
}

// Decrease map size
function MapDecrease() {
    if (mapSize > 200) { // Set a lower limit for the map size
        mapSize -= 100;
        canvas.width = mapSize;
        canvas.height = mapSize;
    }
}