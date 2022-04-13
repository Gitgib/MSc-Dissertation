<?php

    require_once 'includes/session_timeout.php';
    require_once  'includes/connection.php';

    $user = $_SESSION['username'];
    $conn = dbConnect('read');
    $stmt = $conn->stmt_init();
    $sql = 'SELECT userId FROM creator WHERE username=?';
    if ($stmt->prepare($sql)) {
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = mysqli_stmt_get_result($stmt);
    }

    if (!$result) {
        $error = $conn->error;
    } else {
        $row = $result->fetch_assoc();
        $userId = $row['userId'];
        $_SESSION['userId'] = $userId;
    }

    $sql = 'SELECT boardId, title FROM board WHERE userId=?';
    if ($stmt->prepare($sql)) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $boards = mysqli_stmt_get_result($stmt);
        $myBoards = $boards;
    }

    if (!$boards) {
        $error = $conn->error;
    } else {
        $numRows = $boards->num_rows;
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
            <p><b>Username:</b>
                <?php
                echo $_SESSION['username'];
                include 'includes/logout.php';
                ?>
            </p>
        </li>
    </ul>
</header>


<?php
if (isset($error)) {
    echo "<p>$error</p>";
} else {
    switch($numRows) {
        case 0: ?>
            <p id="noBoards" class="warning">There are no boards. Please create a board first.</p>
            <?php break;
        case $numRows > 0: ?>
            <div class="create">
                <form method="post" action="game.php">
                    <ul>
                        <li class="form-row">
                            <p>Choose a board and the number of teams</p>
                        </li>
                        <li class="form-row">
                            <select name="boards" id="boards">
                                <option value="">Select a board</option>
                                <?php
                                while ($row = $myBoards->fetch_assoc()) { ?>
                                    <option value="<?= $row['boardId']; ?>"><?= $row['title']; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        <li class="form-row">
                            <select name="teams" id="teamNum">
                                <option value="">Select number of teams</option>
                                <?php for ($i = 0; $i <= 10; $i++) : ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </li>
                        <li class="form-row">
                            <input type="submit" name="create" value="Create Game" onclick="return empty()">
                        </li>
                    </ul>
                </form>
            </div>
        <?php break;
    } } ?>

<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="boardCreate.php" title="Create a board">Create a board</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
    </ul>
</footer>
<script type="text/javascript">

    function empty() {
        var boardSelect;
        var teamSelect;
        boardSelect = document.getElementById("boards").value;
        teamSelect = document.getElementById("teamNum").value;
        if ((boardSelect == '') || (teamSelect == '')) {
            alert("Please select a value for both fields");
            return false;
        }
    }

</script>
</body>
</html>