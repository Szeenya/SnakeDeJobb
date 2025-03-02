const canvas = document.querySelector('canvas');
const ctx = canvas.getContext('2d');
const gameContainer = document.querySelector('.game_container');

const squareSize = 20; // A négyzetek mérete pixelben
let snake = [{ x: 10, y: 10 }];
let direction = { x: 0, y: 0 };
let food = { x: 15, y: 15, color: 'red', points: 1 };
let greyFood = { x: 5, y: 5, color: 'grey', points: 15 };
let score = 0;
let gameInterval;
let mapSize = 400;
let lives = 3;
let speed = 200; // A játék sebessége (200 = 0.2 mp)
let speedTimeout;

let greyFoodMoveCounter = 0;
let shields = 0;
let isGreyFoodActive = false;

// Módosítsd a draw függvényt:
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Check if dark mode is active
    const isDarkMode = document.body.classList.contains('dark-mode');
    
    // Draw checkerboard pattern with different colors based on mode
    for (let i = 0; i < canvas.width / squareSize; i++) {
        for (let j = 0; j < canvas.height / squareSize; j++) {
            if ((i + j) % 2 === 0) {
                if (isDarkMode) {
                    ctx.fillStyle = '#133A1B'; // Dark mode lighter squares
                } else {
                    ctx.fillStyle = '#90EE90'; // Light mode lighter squares
                }
                ctx.fillRect(i * squareSize, j * squareSize, squareSize, squareSize);
            } else if (isDarkMode) {
                ctx.fillStyle = '#011B10'; // Dark mode darker squares
                ctx.fillRect(i * squareSize, j * squareSize, squareSize, squareSize);
            }
        }
    }
    
    // Draw snake
    snake.forEach((segment, index) => {
        ctx.fillStyle = index === 0 ? '#0047AB' : '#6495ED'; // Changed from '#228B22' and '#32CD32'
        ctx.fillRect(segment.x * squareSize, segment.y * squareSize, squareSize - 2, squareSize - 2);
    });

    // Draw food
    if (isGreyFoodActive) {
        ctx.font = `${squareSize}px Arial`;
        ctx.fillText('🐭', greyFood.x * squareSize - 4, (greyFood.y + 0.8) * squareSize);
    } else {
        if (food.color === 'red') {
            ctx.font = `${squareSize}px Arial`;
            ctx.fillText('🍎', food.x * squareSize - 3, (food.y + 0.8) * squareSize);
        } else if (food.color === 'yellow') {
            ctx.font = `${squareSize}px Arial`;
            ctx.fillText('🍋', food.x * squareSize - 4, (food.y + 0.8) * squareSize);
        } else if (food.color === 'green') {
            ctx.font = `${squareSize}px Arial`;
            ctx.fillText('🍉', food.x * squareSize - 4, (food.y + 0.8) * squareSize);
        } else if (food.color === 'brown') {
            ctx.font = `${squareSize}px Arial`;
            ctx.fillText('🍪', food.x * squareSize - 3, (food.y + 0.8) * squareSize);
        } else if (food.color === 'lightblue') {
            ctx.font = `${squareSize}px Arial`;
            ctx.fillText('🛡️', food.x * squareSize - 1, (food.y + 0.8) * squareSize);
        } else {
            ctx.fillStyle = food.color;
            ctx.fillRect(food.x * squareSize, food.y * squareSize, squareSize - 2, squareSize - 2);
        }
    }
}

function update() {
    const head = { x: snake[0].x + direction.x, y: snake[0].y + direction.y };

    // Check for collision with food
    if (!isGreyFoodActive && head.x === food.x && head.y === food.y) {
        score += food.points;
        document.getElementById('score').innerText = `Pont: ${score}`;
        snake.unshift(head);

        // Handle different food types
        if (food.color === 'yellow') {
            // If the food is yellow, increase speed for 3 seconds
            clearInterval(gameInterval);
            speed = 100; // Increase speed
            gameInterval = setInterval(() => {
                update();
                draw();
            }, speed);

            // Revert speed back to normal after 3 seconds
            clearTimeout(speedTimeout);
            speedTimeout = setTimeout(() => {
                clearInterval(gameInterval);
                speed = 200; // Normal speed
                gameInterval = setInterval(() => {
                    update();
                    draw();
                }, speed);
            }, 3000);
        } else if (food.color === 'green') {
            // If the food is green, grow the snake by 2 segments
            // Add two new segments at the tail end
            const tail = snake[snake.length - 1];
            snake.push({ x: tail.x, y: tail.y });
            snake.push({ x: tail.x, y: tail.y });
        } else if (food.color === 'lightblue') {
            shields++;
            updateShieldsDisplay();
        } else if (food.color === 'brown') {
            // If the food is brown, decrease speed for 3 seconds
            clearInterval(gameInterval);
            speed = 300; // Decrease speed (lassabb sebesség, nagyobb szám = lassabb mozgás)
            gameInterval = setInterval(() => {
                update();
                draw();
            }, speed);

            // Revert speed back to normal after 3 seconds
            clearTimeout(speedTimeout);
            speedTimeout = setTimeout(() => {
                clearInterval(gameInterval);
                speed = 200; // Normal speed
                gameInterval = setInterval(() => {
                    update();
                    draw();
                }, speed);
            }, 3000);
        }

        placeFood();
    } else if (isGreyFoodActive && head.x === greyFood.x && head.y === greyFood.y) {
        score += greyFood.points;
        document.getElementById('score').innerText = `Score: ${score}`;
        snake.unshift(head);
        placeFood(); // Changed from placeGreyFood to placeFood
    } else {
        snake.unshift(head);
        snake.pop();
    }

    // Move grey food away from the snake
    if (isGreyFoodActive) {
        moveGreyFood();
    }

    // Check for collision with walls or self
    if (head.x < 0 || head.x >= canvas.width / 20 || head.y < 0 || head.y >= canvas.height / 20 || collision(head)) {
        if (shields > 0) {
            shields--;
            updateShieldsDisplay();
            resetSnake();
        } else {
            lives--;
            updateLivesDisplay();
            if (lives > 0) {
                resetSnake();
                // Reset speed when losing a life
                clearInterval(gameInterval);
                speed = 200; // Reset to normal speed
                gameInterval = setInterval(() => {
                    update();
                    draw();
                }, speed);
            } else {
                clearInterval(gameInterval);
                speed = 200; // Reset to normal speed
                saveScore(score); // Save the score before resetting
                alert('Game Over! Your score: ' + score);
                resetGame();
            }
        }
    }
}

function collision(head) {
    return snake.slice(1).some(segment => segment.x === head.x && segment.y === head.y);
}

function placeFood() {
    const random = Math.random();
    
    // Generate new food position first
    let newX = Math.floor(Math.random() * (canvas.width / 20));
    let newY = Math.floor(Math.random() * (canvas.height / 20));
    
    // Keep generating new position if it collides with snake
    while (snake.some(segment => segment.x === newX && segment.y === newY)) {
        newX = Math.floor(Math.random() * (canvas.width / 20));
        newY = Math.floor(Math.random() * (canvas.height / 20));
    }

    // Determine food type and set properties
    if (random < 0.20) { // Red food - 20% chance
        isGreyFoodActive = false;
        food.color = 'red'; //apple
        food.points = 1;
        food.x = newX;
        food.y = newY;
    } else if (random < 0.35) { // Green food - 15% chance
        isGreyFoodActive = false;
        food.color = 'green'; //melon
        food.points = 5;
        food.x = newX;
        food.y = newY;
    } else if (random < 0.50) { // Yellow food - 15% chance
        isGreyFoodActive = false;
        food.color = 'yellow'; //lemon
        food.points = 10;
        food.x = newX;
        food.y = newY;
    } else if (random < 0.65) { // Blue food (shield) - 15% chance
        isGreyFoodActive = false;
        food.color = 'lightblue'; //shield 
        food.points = 0;
        food.x = newX;
        food.y = newY;
    } else if (random < 0.85) { // Brown food (slow) - 20% chance
        isGreyFoodActive = false;
        food.color = 'brown'; //süti
        food.points = 7;
        food.x = newX;
        food.y = newY;
    } else { // Grey food - 15% chance
        isGreyFoodActive = true;
        do {
            greyFood.x = Math.floor(Math.random() * (canvas.width / 20));
            greyFood.y = Math.floor(Math.random() * (canvas.height / 20));
        } while (collision(greyFood) || isNearWall(greyFood));
        greyFood.color = 'grey';
        greyFood.points = 15;
    }
}

function placeGreyFood() {
    do {
        greyFood.x = Math.floor(Math.random() * (canvas.width / 20));
        greyFood.y = Math.floor(Math.random() * (canvas.height / 20));
    } while (collision(greyFood) || isNearWall(greyFood));
}

function isNearWall(food) {
    return food.x < 2 || food.x >= canvas.width / 20 - 2 || food.y < 2 || food.y >= canvas.height / 20 - 2;
}

function moveGreyFood() {
    // Only move every third update
    greyFoodMoveCounter++;
    if (greyFoodMoveCounter % 3 !== 0) return;

    const distanceX = greyFood.x - snake[0].x;
    const distanceY = greyFood.y - snake[0].y;

    // Store previous position in case we need to revert
    const prevX = greyFood.x;
    const prevY = greyFood.y;

    // Move away from snake
    if (Math.abs(distanceX) > Math.abs(distanceY)) {
        greyFood.x += distanceX > 0 ? 1 : -1;
    } else {
        greyFood.y += distanceY > 0 ? 1 : -1;
    }

    // Check if new position is near wall or collides with snake
    if (isNearWall(greyFood) || collision(greyFood)) {
        // Revert to previous position
        greyFood.x = prevX;
        greyFood.y = prevY;

        // Try moving in the other direction
        if (Math.abs(distanceX) > Math.abs(distanceY)) {
            greyFood.y += distanceY > 0 ? 1 : -1;
            // If still invalid, revert
            if (isNearWall(greyFood) || collision(greyFood)) {
                greyFood.y = prevY;
            }
        } else {
            greyFood.x += distanceX > 0 ? 1 : -1;
            // If still invalid, revert
            if (isNearWall(greyFood) || collision(greyFood)) {
                greyFood.x = prevX;
            }
        }
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

function startGame() {
    // Position snake at the center of the canvas
    const centerX = Math.floor((canvas.width / 20) / 2);
    const centerY = Math.floor((canvas.height / 20) / 2);
    snake = [{ x: centerX, y: centerY }];
    direction = { x: 0, y: 0 };
    score = 0;
    lives = 3;
    shields = 0;
    isGreyFoodActive = false;
    greyFoodMoveCounter = 0;
    document.getElementById('score').innerText = `Score: ${score}`;
    updateLivesDisplay();
    updateShieldsDisplay();
    placeFood();
    gameInterval = setInterval(() => {
        update();
        draw();
    }, speed);
}

function resetGame() {
    clearInterval(gameInterval);
    speed = 200; // Reset to normal speed
    document.getElementById('resetButton').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
}

function resetSnake() {
    // Position snake at the center of the canvas
    const centerX = Math.floor((canvas.width / 20) / 2);
    const centerY = Math.floor((canvas.height / 20) / 2);
    snake = [{ x: centerX, y: centerY }];
    direction = { x: 0, y: 0 };
}

function updateLivesDisplay() {
    const livesContainer = document.getElementById('lives');
    livesContainer.innerHTML = ''; 
    livesContainer.style.position = 'static'; // Remove absolute positioning
    livesContainer.style.margin = '5px 0'; // Add some margin
    for (let i = 0; i < lives; i++) {
        const heart = document.createElement('span');
        heart.innerHTML = '❤️';
        livesContainer.appendChild(heart);
    }
}

function updateShieldsDisplay() {
    let shieldsContainer = document.getElementById('shields');
    if (!shieldsContainer) {
        shieldsContainer = document.createElement('div');
        shieldsContainer.id = 'shields';
        document.getElementById('lives').after(shieldsContainer);
    }
    
    shieldsContainer.style.position = 'static'; // Remove absolute positioning
    shieldsContainer.style.margin = '5px 0'; // Add some margin
    
    if (shields > 0) {
        shieldsContainer.style.display = 'block';
        shieldsContainer.innerHTML = '🛡️'.repeat(shields);
    } else {
        shieldsContainer.style.display = 'none';
    }
}

function addPoints() {
    score += 10;
    document.getElementById('score').textContent = 'Pont: ' + score;
}

document.getElementById('startButton').addEventListener('click', () => {
    startGame();
    document.getElementById('startButton').style.display = 'none';
    document.getElementById('resetButton').style.display = 'block';
});

document.getElementById('resetButton').addEventListener('click', resetGame);

document.addEventListener('keydown', changeDirection);

// Add event listener for dark mode changes
document.getElementById('darkMode_toggle').addEventListener('change', () => {
    draw(); // Redraw canvas when dark mode changes
});




