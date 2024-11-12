<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Dot Game</title>
    <link rel="stylesheet" href="design.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('images/use1.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            font-family: 'Arial', sans-serif;
            flex-direction: column;
        }

        #gameContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        /* Instructions Styling */
        .instructions {
            text-align: center;
            font-size: 1.1rem;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
            max-width: 400px;
        }

        /* Game Area Styling */
        #gameArea {
            width: 400px;
            height: 400px;
            border: 2px solid #fff;
            position: relative;
            background-color: rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.7);
        }

        /* Dot Styling */
        #dot {
            width: 20px;
            height: 20px;
            background-color: #f9bc35;
            position: absolute;
            transition: top 0.1s, left 0.1s;
            border-radius: 50%;
        }

        /* Button Styling */
        .button {
            padding: 12px 25px;
            background-color: rgba(249, 188, 53, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: rgba(252, 168, 108, 0.8);
            transform: scale(1.05);
        }

        .button:active {
            transform: scale(1);
        }

        /* Score and Timer Display */
        .score, .timer {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Key Guide Styling */
        .key-guide {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px;
            border-radius: 6px;
            color: white;
            font-size: 0.9rem;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
        }

        .key-guide span {
            display: block;
            margin: 2px 0;
        }
    </style>
</head>
<body>

<div id="gameContainer">
    <!-- Instructions -->
    <div class="instructions">
        Use the arrow keys to move the yellow dot. Try to reach the edges of the game area to score points before time runs out!
    </div>

    <!-- Home and Reset Buttons -->
    <a href="register.php" class="button">Home</a>
    <button class="button" onclick="resetGame()">Reset Game</button>

    <!-- Game Area -->
    <div id="gameArea">
        <div id="dot"></div>
    </div>

    <!-- Score and Timer Display -->
    <div class="score">Score: <span id="score">0</span></div>
    <div class="timer">Time Left: <span id="timer">30</span> seconds</div>
</div>

<!-- Key Guide in the lower right corner -->
<div class="key-guide">
    <span>Use Arrow Keys</span>
    <span>&#8593; &#8595; &#8592; &#8594;</span>
</div>

<script>
    const dot = document.getElementById('dot');
    const gameArea = document.getElementById('gameArea');
    const scoreDisplay = document.getElementById('score');
    const timerDisplay = document.getElementById('timer');

    let posX = Math.random() * (gameArea.clientWidth - dot.clientWidth);
    let posY = Math.random() * (gameArea.clientHeight - dot.clientHeight);
    let score = 0;
    let timeLeft = 30; // Game time in seconds
    const moveAmount = 10;
    const maxWidth = gameArea.clientWidth - dot.clientWidth;
    const maxHeight = gameArea.clientHeight - dot.clientHeight;

    // Function to move dot to random position within the game area
    function randomizeDotPosition() {
        posX = Math.floor(Math.random() * maxWidth);
        posY = Math.floor(Math.random() * maxHeight);
        moveDot();
    }

    // Function to update dot position
    function moveDot() {
        dot.style.left = posX + 'px';
        dot.style.top = posY + 'px';
    }

    // Reset game function
    function resetGame() {
        score = 0;
        timeLeft = 30;
        scoreDisplay.textContent = score;
        timerDisplay.textContent = timeLeft;
        randomizeDotPosition();
        clearInterval(gameInterval);
        startGame();
    }

    // Timer countdown
    function startGame() {
        gameInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                timerDisplay.textContent = timeLeft;
            } else {
                clearInterval(gameInterval);
                alert("Time's up! Final score: " + score);
            }
        }, 1000);
    }

    // Event listener for arrow keys
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowUp' && posY > 0) {
            posY -= moveAmount;
        } else if (e.key === 'ArrowDown' && posY < maxHeight) {
            posY += moveAmount;
        } else if (e.key === 'ArrowLeft' && posX > 0) {
            posX -= moveAmount;
        } else if (e.key === 'ArrowRight' && posX < maxWidth) {
            posX += moveAmount;
        }
        moveDot();

        // Check if dot has reached the edge (goal condition)
        if ((posX <= 0 || posX >= maxWidth) || (posY <= 0 || posY >= maxHeight)) {
            score++;
            scoreDisplay.textContent = score;
            randomizeDotPosition();
        }
    });

    // Start the game
    startGame();
</script>

</body>
</html>
