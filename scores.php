<?php
    require_once 'includes/session_timeout.php';
    require_once  'includes/connection.php';
    $errors = [];
    $missing = [];
    if (isset($_POST['save'])) {
        $expected = ['team', 'points'];
        $required= [];

        require 'includes/processform.php';


        if ((!$missing) && (!$errors)) {
            if (isset($_POST['team'])) {
                $team = $_POST['team'];
                $points = $_POST['points'];
                $gameId = $_SESSION['lastId'];
                $teamNum = $_SESSION['teamNum'];

                $conn = dbConnect('write');
                $stmt = $conn->stmt_init();
                $sql = 'INSERT INTO player (name, totalPoints, gameId) VALUES (? ,?, ?)';
                if ($stmt->prepare($sql)) {
                    for ($i = 0; $i < $teamNum; $i++) {
                        $stmt->bind_param('sii', $team[$i], $points[$i], $gameId);
                        $stmt->execute();
                        if ($stmt->affected_rows > 0) {
                            $OK = true;
                        } else {
                            $error = $stmt->error;
                        }
                    }
                }

                $sql = 'SELECT name, totalPoints FROM player WHERE gameId=? ORDER BY totalPoints DESC';
                if ($stmt->prepare($sql)) {
                    $stmt->bind_param('i', $gameId);
                    $stmt->execute();
                    $results = mysqli_stmt_get_result($stmt);
                    if (!$results) {
                        $error = $conn->error;
                    } else {
                        $numRows = $results->num_rows;
                    }
                }
            }
        }
    }
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
    <a href="homelogged.php" title="Return to the front page">
        <img src="images/CITY-College-Sheffield.jpg" alt="CITY Jeopardy" />
    </a>
    <p id="motto">Welcome to <span class="logo">CITY Jeopardy!</span><br>The fun way to learn</p>
    <ul>
        <li>
            <?php
            if (isset($_SESSION['authenticated']));
            ?>
            <p><b>Account:</b>
                <?php
                echo $_SESSION['username'];
                include 'includes/logout.php';
                ?>
            </p>
        </li>
    </ul>
</header>

<div id="main" role="main">
    <?php
    if (isset($error)) {
        echo "<p>$error</p>";
    } else {
        if (isset($numRows)) {
            echo "<table class='scores'><tr><th>Team</th><th>Points</th></tr>";
            while ($row = $results->fetch_assoc()) {
                echo "<tr><td>" . $row['name'] . "</td><td>" . $row['totalPoints'] . "</td></tr>";
            }
            echo "</table>";
        }/* else {
            header('location: homelogged.php');
        }*/
    }
    ?>
</div>

<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="gameCreate.php" title="Create a game">Create a Game</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
    </ul>
</footer>

</body>
</html>