<?php

    require_once 'includes/session_timeout.php';
    require_once  'includes/connection.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CITY Jeopardy</title>
    <link rel="stylesheet" href="styles/mystyle.css">
</head>
<body>
<header id="header" class="group" role="banner">
    <a href="#" title="Return to the front page">
        <img src="images/CITY-College-Sheffield.jpg" alt="CITY Jeopardy" />
    </a>
    <p id="motto">Welcome to <span class="logo">CITY Jeopardy!</span><br>The fun way to learn</p>
    <ul>
        <li>
            <?php
            if (isset($_SESSION['authenticated']));
            ?>
            <p><b>Username:</b>
                <?php
                echo $_SESSION['username'];
                include 'includes/logout.php';
                ?>
            </p>
        </li>
    </ul>
</header>

<div id="main" class="group">
    <div id="content" role="main">
        <p>CITY Jeopardy offers you a funny and interesting way to test your knowledge on any topic you want. It is based on the synonymous famous American
            Quiz show and allows you to create and play your own boards with up to 10 players. Don't hold back! Create and play a board now!
        </p>
        <ul>
            <li>
                <p>Do you want to play on an existing board?</p><a href="gameCreate.php" title="Create a game">Create a game</a>
            </li>
            <li>
                <p>Do you want to create your own board?</p><a href="boardCreate.php" title="Create a board">Create a board</a>
            </li>
        </ul>
    </div>
</div>
<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="gameCreate.php" title="Create a game">Create</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
    </ul>
</footer>
</body>
</html>