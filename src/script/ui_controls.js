function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.hamburger-btn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let dropdown of dropdowns) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
}

// Map size control functions
function MapIncrease() {
    mapSize += 100;
    canvas.width = mapSize;
    canvas.height = mapSize;
    gameContainer.style.width = `${mapSize + 50}px`; // Adjust container width
}

function MapDecrease() {
    if (mapSize > 200) { // Prevent the map from becoming too small
        mapSize -= 100;
        canvas.width = mapSize;
        canvas.height = mapSize;
        gameContainer.style.width = `${mapSize + 50}px`; // Adjust container width
    }
}

function saveScore(score) {
    fetch('update_score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `score=${score}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Score saved successfully');
        } else {
            console.error('Error saving score:', data.error);
        }
    })
    .catch(error => {
        console.error('Error saving score:', error);
    });
}

// Leaderboard functionality
function loadLeaderboard() {
    fetch('get_leaderboard.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.getElementById('leaderboardTable');
                tableBody.innerHTML = '';
                
                // Get the current user's username
                const usernameElement = document.querySelector('.username');
                const currentUsername = usernameElement.textContent.trim();
                
                // Update glow effect based on first place
                if (data.data.length > 0 && data.data[0].username === currentUsername) {
                    usernameElement.classList.add('first-place');
                } else {
                    usernameElement.classList.remove('first-place');
                }
                
                // Populate leaderboard table
                data.data.forEach((entry, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${entry.username}</td>
                        <td>${entry.personal_best}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => console.error('Error loading leaderboard:', error));
}

// Add event listener to load leaderboard when modal opens
document.getElementById('leaderboardModal').addEventListener('show.bs.modal', loadLeaderboard);