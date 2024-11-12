<?php
// Simple redirection page
header("refresh:5;url=game.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link rel="stylesheet" href="design.css"> <!-- Link to your design.css -->
    <style>
        /* Ensure the background matches the other pages */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('images/use1.jpg'); /* Background image */
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            font-family: 'Arial', sans-serif;
        }

        .redirect-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent dark background */
            border-radius: 1rem;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            text-align: center;
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .redirect-container:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.8);
        }

        h1 {
            font-size: 1.8em;
            color: #f9bc35; /* Gold color for the header */
            margin-bottom: 1.5em;
            font-weight: bold;
        }

        p {
            font-size: 1.1em;
            color: white;
        }

        a {
            color: #f9bc35;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Loading Screen */
        .loading-screen {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 999;
        }

        /* Style for the home button (fixed at top-left) */
        .home-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: rgba(249, 188, 53, 0.8);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            z-index: 10; /* Ensure the button is above other elements */
        }

        .home-button:hover {
            background-color: rgba(252, 168, 108, 0.8);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <!-- Redirect Container -->
    <div class="redirect-container">
        <h1>You will be redirected to the game. Please wait for 5 seconds...</h1>
        <p>If you are not redirected, <a href="game.php">click here</a> to go to the game.</p>
    </div>

    <!-- Home Button -->
    <button class="home-button" onclick="window.location.href='index.php';">Home</button>

</body>
</html>
