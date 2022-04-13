<?php
    require_once 'includes/session_timeout.php';
    require_once  'includes/connection.php';
    $errors = [];
    $missing = [];
    if (isset($_POST['create'])) {

        $boardId = $_POST['boards'];
        $expected = ['boards', 'teams'];
        $required = ['boards'];
        $conn = dbConnect('write');
        $stmt = $conn->stmt_init();

        require 'includes/processform.php';

        if (!$missing && !$errors) {
            $date = date('d-m-y');
            $teamNum = $_POST['teams'];
            $boards = $_POST['boards'];
            $userId = $_SESSION['userId'];

            $sql = "INSERT INTO game (date, number_of_teams, userId) VALUES (?, ?, ?)";
            if ($stmt->prepare($sql)) {
                $stmt->bind_param('sii', $date, $teamNum, $userId);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $OK = true;
                    $_SESSION['lastId'] = $conn->insert_id;
                    $_SESSION['teamNum'] = $teamNum;
                } else {
                    $error = $stmt->error;
                }
            }
        }

        $sql = "SELECT board.title, category.text AS category, clue.text AS clue, clue.response, clue.points FROM clue 
INNER JOIN category ON clue.categoryId = category.categoryId INNER JOIN board ON board.boardId = category.boardId 
WHERE category.boardId = ? ORDER BY clue.points ASC, category";
        if ($stmt->prepare($sql)) {
            $stmt->bind_param('i', $boardId);
            $stmt->execute();
            $result = mysqli_stmt_get_result($stmt);
        }

        if (!$result) {
            $error = $conn->error;
        } else {
            $data = array();
            $numRows = $result->num_rows;
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
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
<body class="games">
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

<div>
    <?php if ($missing || $errors) { ?>
        <?php if (isset($missing)) { ?>
            <p class="warning">There was an error fetching the results.</p>
        <?php } ?>
    <?php } elseif (isset($_POST['save']) && !$missing && !$errors) { ?>
        <p class="warning">Game was saved successfully</p>
    <?php } ?>
        <?php
        if (isset($error)) {
            echo "<p>$error</p>";
        } elseif ($numRows > 0) { ?>
            <div style="height:70px; padding-left:20px; padding-right:20px; margin:20px 0; text-align: center">
                <textarea cols="30" rows="1" readonly style="text-align: center"><?php echo $data[0]['title']; ?></textarea>
            </div>
            <div class="container">
                <div id="category-cell-1" class="category" data-row="0" data-col="2">
                    <textarea readonly><?php echo $data[0]['category']; ?></textarea>
                </div>
                <div id="category-cell-2" data-row="0" data-col="3">
                    <textarea readonly><?php echo $data[1]['category']?></textarea>
                </div>
                <div id="category-cell-3" data-row="0" data-col="4">
                    <textarea readonly><?php echo $data[2]['category']?></textarea>
                </div>
                <div id="category-cell-4" data-row="0" data-col="5">
                    <textarea readonly><?php echo $data[3]['category']?></textarea>
                </div>
                <div id="category-cell-5" data-row="0" data-col="6">
                    <textarea readonly><?php echo $data[4]['category']?></textarea>
                </div>
                <div id="points-cell-1" class="points" data-row="1" data-col="0">
                    <textarea class="points" readonly><?php echo $data[0]['points']; ?></textarea>
                </div>
                <div id="cell-group-1" data-row="1" data-col="1">
                    <textarea cols="25" rows="4" readonly id="clue-0" class="modal-clue"><?php echo $data[0]['points']; ?></textarea>
                </div>
                <div id="cell-group-2" data-row="1" data-col="2">
                    <textarea cols="25" rows="4" readonly id="clue-1" class="modal-clue"><?php echo $data[1]['points']; ?></textarea>
                </div>
                <div id="cell-group-3" data-row="1" data-col="3">
                    <textarea cols="25" rows="4" readonly id="clue-2" class="modal-clue"><?php echo $data[2]['points']; ?></textarea>
                </div>
                <div id="cell-group-4" data-row="1" data-col="4">
                    <textarea cols="25" rows="4" readonly id="clue-3" class="modal-clue"><?php echo $data[3]['points']; ?></textarea>
                </div>
                <div id="cell-group-5" data-row="1" data-col="5">
                    <textarea cols="25" rows="4" readonly id="clue-4" class="modal-clue"><?php echo $data[4]['points']; ?></textarea>
                </div>
                <div id="points-cell-2" data-row="2" data-col="0">
                    <textarea class="points" readonly><?php echo $data[5]['points']; ?></textarea>
                </div>
                <div id="cell-group-6" data-row="2" data-col="1">
                    <textarea cols="25" rows="4" readonly id="clue-5" class="modal-clue"><?php echo $data[5]['points']; ?></textarea>
                </div>
                <div id="cell-group-7" data-row="2" data-col="2">
                    <textarea cols="25" rows="4" readonly id="clue-6" class="modal-clue"><?php echo $data[6]['points']; ?></textarea>
                </div>
                <div id="cell-group-8" data-row="2" data-col="3">
                    <textarea cols="25" rows="4" readonly id="clue-7" class="modal-clue"><?php echo $data[7]['points']; ?></textarea>
                </div>
                <div id="cell-group-9" data-row="2" data-col="4">
                    <textarea cols="25" rows="4" readonly id="clue-8" class="modal-clue"><?php echo $data[8]['points']; ?></textarea>
                </div>
                <div id="cell-group-10" data-row="2" data-col="5">
                    <textarea cols="25" rows="4" readonly id="clue-9" class="modal-clue"><?php echo $data[9]['points']; ?></textarea>
                </div>
                <div id="points-cell-3" data-row="3" data-col="0">
                    <textarea class="points" readonly><?php echo $data[10]['points']; ?></textarea>
                </div>
                <div id="cell-group-11" data-row="3" data-col="1">
                    <textarea cols="25" rows="4" readonly id="clue-10" class="modal-clue"><?php echo $data[10]['points']; ?></textarea>
                </div>
                <div id="cell-group-12" data-row="3" data-col="2">
                    <textarea cols="25" rows="4" readonly id="clue-11" class="modal-clue"><?php echo $data[11]['points']; ?></textarea>
                </div>
                <div id="cell-group-13" data-row="3" data-col="3">
                    <textarea cols="25" rows="4" readonly id="clue-12" class="modal-clue"><?php echo $data[12]['points']; ?></textarea>
                </div>
                <div id="cell-group-14" data-row="3" data-col="4">
                    <textarea cols="25" rows="4" readonly id="clue-13" class="modal-clue"><?php echo $data[13]['points']; ?></textarea>
                </div>
                <div id="cell-group-15" data-row="3" data-col="5">
                    <textarea cols="25" rows="4" readonly id="clue-14" class="modal-clue"><?php echo $data[14]['points']; ?></textarea>
                </div>
                <div id="points-cell-4" data-row="4" data-col="0">
                    <textarea class="points" readonly><?php echo $data[15]['points']; ?></textarea>
                </div>
                <div id="cell-group-16" data-row="4" data-col="1">
                    <textarea cols="25" rows="4" readonly id="clue-15" class="modal-clue"><?php echo $data[15]['points']; ?></textarea>
                </div>
                <div id="cell-group-17" data-row="4" data-col="2">
                    <textarea cols="25" rows="4" readonly id="clue-16" class="modal-clue"><?php echo $data[16]['points']; ?></textarea>
                </div>
                <div id="cell-group-18" data-row="4" data-col="3">
                    <textarea cols="25" rows="4" readonly id="clue-17" class="modal-clue"><?php echo $data[17]['points']; ?></textarea>
                </div>
                <div id="cell-group-19" data-row="4" data-col="4">
                    <textarea cols="25" rows="4" readonly id="clue-18" class="modal-clue"><?php echo $data[18]['points']; ?></textarea>
                </div>
                <div id="cell-group-20" data-row="4" data-col="5">
                    <textarea cols="25" rows="4" readonly id="clue-19" class="modal-clue"><?php echo $data[19]['points']; ?></textarea>
                </div>
                <div id="points-cell-5" data-row="5" data-col="0">
                    <textarea class="points" readonly><?php echo $data[20]['points']; ?></textarea>
                </div>
                <div id="cell-group-21" data-row="5" data-col="1">
                    <textarea cols="25" rows="4" readonly id="clue-20" class="modal-clue"><?php echo $data[20]['points']; ?></textarea>
                </div>
                <div id="cell-group-22" data-row="5" data-col="2">
                    <textarea cols="25" rows="4" readonly id="clue-21" class="modal-clue"><?php echo $data[21]['points']; ?></textarea>
                </div>
                <div id="cell-group-23" data-row="5" data-col="3">
                    <textarea cols="25" rows="4" readonly id="clue-22" class="modal-clue"><?php echo $data[22]['points']; ?></textarea>
                </div>
                <div id="cell-group-24" data-row="5" data-col="4">
                    <textarea cols="25" rows="4" readonly id="clue-23" class="modal-clue"><?php echo $data[23]['points']; ?></textarea>
                </div>
                <div id="cell-group-25" data-row="5" data-col="5">
                    <textarea cols="25" rows="4" readonly id="clue-24" class="modal-clue"><?php echo $data[24]['points']; ?></textarea>
                </div>
            </div>

            <div id="myModal" class="modal">

                <span class="close">&times;</span>
                <div id="modal-header">
                    <div class="modal-hint">Press 'R' to reveal the response</div>
                    <div id="modal-points"></div>
                    <div id="modal-exit" class="modal-hint">Press X on the top right corner to exit</div>
                </div>
                <div id="modal-clue"></div>
                <div id="modal-response"></div>

            </div>

        <?php } ?>
    <?php if (isset($teamNum) && $teamNum > 0) { ?>
        <form method="post" action="scores.php">
            <ul id="teams"></ul>
        </form>
    <?php } ?>
</div>
<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="boardCreate.php.php" title="Create a board">Create</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
    </ul>
</footer>
<script>
    let teams = {
        create: function () {
            let target = document.getElementById("teams")
                ,teamNum = '<?= $_SESSION["teamNum"] ?>'
                ,i;
            if ((typeof teamNum !== 'undefined') && teamNum > 0) {
                for (i = 0; i < teamNum; i++) {
                    target.innerHTML += '<li><input type="text" name="team[' + i + ']" value="team' + i + '" max="15"></li>';
                    target.innerHTML += '<li><input type="number" class="teamPoints" name="points[' + i + ']" value="0" min="0"></li>';
                    target.innerHTML += '<li><button class="addPoints" type="button">Add Points</li>';
                }
                target.innerHTML += '<li><input type="submit" value="Save" name="save"></li>'
            }
        }

    };
    document.addEventListener("onload", teams.create(), false);

    let modal = document.getElementById("myModal");
    let clues = document.querySelectorAll(".modal-clue");
    let data = <?=json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP)?>;
    let categories = eval(data);

    function prompt(input_event, category, points, clue, response) {
        input_event.setAttribute("style", "opacity: .5;");
        modal.style.display = "block";
        document.getElementById("modal-points").innerHTML = category + " for " + points;
        document.getElementById("modal-clue").innerHTML = clue;
        document.getElementById("modal-response").innerHTML = response;
        addEvent(points);
    }

    let span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
        document.getElementById("modal-response").setAttribute("style", "visibility:hidden;");
    };

    document.onkeyup = function () {
        if ((event.code === 'KeyR') && (modal.hasAttribute("style"))) {
            document.getElementById("modal-response").setAttribute("style", "visibility: visible;");
            /*document.getElementById(input_event).setAttribute("style", "opacity: .5;");*/
        }
    };

    for (let j = 0; j < categories.length; j++) {
        let object = categories[j];
        clues[j].addEventListener("click", function () {
            prompt(clues[j], object.category, object.points, object.clue, object.response);
        });
    }

    let teamPoints = document.getElementsByClassName('teamPoints');
    let button = document.getElementsByClassName('addPoints');
    let teamsNum = button.length;

    function handler(evt) {
        if (teamPoints[evt.currentTarget.i]) {
            teamPoints[evt.currentTarget.i].stepUp(evt.currentTarget.points);
        }
    }

    function addEvent(points) {
        for (let i = 0; i < teamsNum; i++) {
            button[i].i = i;
            button[i].points = points;
            button[i].addEventListener("click", handler, {once: true});
        }
    }

</script>
</body>
</html>