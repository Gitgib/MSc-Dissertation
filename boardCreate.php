<?php

    require_once 'includes/session_timeout.php';
    $errors = [];
    $missing = [];
    if (isset($_POST['save'])) {
        $expected = ['title', 'category', 'question', 'answer', 'points'];
        $required = ['title', 'category', 'question', 'answer'];

        require 'includes/processform.php';

        if (!$missing && !$errors) {
            $title = $_POST['title'];
            $category = $_POST['category'];
            $question = $_POST['question'];
            $answer = $_POST['answer'];
            $points = $_POST['points'];

            require_once 'includes/connection.php';
            $OK = false;
            $done = false;
            $user = $_SESSION['username'];
            $userId = $_SESSION['userId'];
            $conn = dbConnect('write');
            $stmt = $conn->stmt_init();
            $sql = "INSERT INTO Board (title, userId) VALUES (?, ?)";
            if ($stmt->prepare($sql)) {
                $stmt->bind_param('si', $title, $userId);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $OK = true;
                    $boardId = $stmt->insert_id;
                } else {
                    $error = $stmt->error;
                }
            }

            $sql = "INSERT INTO category (text, boardId) VALUES (?, ?)";
            if ($stmt->prepare($sql)) {
                foreach ($category as $value) {
                    $stmt->bind_param('si', $value, $boardId);
                    $stmt->execute();
                    if ($stmt->affected_rows > 0) {
                        $OK = true;
                    } else {
                        $error = $stmt->error;
                    }
                }
            }

            $sql = "SELECT categoryId FROM category WHERE boardId=?";
            if ($stmt->prepare($sql)) {
                $stmt->bind_param('i', $boardId);
                $OK = $stmt->execute();
                $result = mysqli_stmt_get_result($stmt);
                $row = $result->fetch_row();
                $catId = $row[0];
            }


            $sql = "INSERT INTO clue (points, text, response, categoryId) VALUES (?, ?, ?, ?)";
            if ($stmt->prepare($sql)) {
                for ($y = 0; $y <= 4; $y++) {
                    $x = 0;
                    for ($i = $catId; $i <= $catId + 4; $i++) {
                        $stmt->bind_param('issi', $points[$y][0], $question[$y][$x], $answer[$y][$x], $i);
                        $stmt->execute();
                        if ($stmt->affected_rows > 0) {
                            $OK = true;
                        } else {
                            $error = $stmt->error;
                        }
                        $x++;
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
            <p class="warning"> Please fix the following required fields:</p>
            <ul>
                <?php
                foreach ($missing as $item) {
                    echo "<li>$item</li>";
                }
                ?>
            </ul>
        <?php } ?>
    <?php } elseif (isset($_POST['save']) && !$missing && !$errors) { ?>
        <p class="warning">Board was created successfully</p>
    <?php } ?>

    <form method="post" id="save-form" class="boardForm">

        <div id="theTitle" style="text-align: center;">
        <textarea autocomplete="off" id="title" tabindex="1" name="title" cols="30" rows="1" id="title" placeholder="Enter Title" style="text-align: center" oninput="noStyle(this.id)"
            <?php if (($missing) && ($_POST['title'] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                echo htmlentities($_POST['title']);
            }?></textarea>
        </div>

        <div class="points-cell-0"></div>

            <div class="category-cell-1" data-row="0" data-col="1">
                <textarea autocomplete="off" name="category[0]" class="category-txt" placeholder="Enter Category Name" id="category[0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['category'][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['category'][0]);
                    } ?></textarea>
            </div>

            <div class="category-cell-2" data-row="0" data-col="2">
                <textarea autocomplete="off" name="category[1]" class="category-txt" placeholder="Enter Category Name" id="category[1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['category'][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['category'][1]);
                    } ?></textarea>
            </div>

            <div class="category-cell-3" data-row="0" data-col="3">
                <textarea autocomplete="off" name="category[2]" class="category-txt" placeholder="Enter Category Name" id="category[2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['category'][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['category'][2]);
                    } ?></textarea>
            </div>

            <div class="category-cell-4" data-row="0" data-col="4">
                <textarea autocomplete="off" name="category[3]" class="category-txt" placeholder="Enter Category Name" id="category[3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['category'][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['category'][3]);
                    } ?></textarea>
            </div>

            <div class="category-cell-5" data-row="0" data-col="5">
                <textarea autocomplete="off" name="category[4]" class="category-txt" placeholder="Enter Category Name" id="category[4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['category'][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['category'][4]);
                    } ?></textarea>
            </div>

            <!-- points -->
            <div class="points-cell-1" data-row="1" data-col="0">
                <input autocomplete="off" name="points[0][0]" type="number" class="points" value="<?php if ($missing || $errors) {
                    echo htmlentities($_POST['points'][0][0]);
                } else {
                    echo '100';
                } ?>" />
            </div>

            <div class="cell-group-1" data-row="1" data-col="1">
                <textarea cols="25" rows="4" autocomplete="off" name="question[0][0]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[0][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][0][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][0][0]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[0][0]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[0][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][0][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][0][0]);
                    } ?></textarea>
            </div>

            <div class="cell-group-2" data-row="1" data-col="2">
                <textarea cols="25" rows="4" autocomplete="off" name="question[0][1]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[0][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][0][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][0][1]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[0][1]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[0][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][0][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][0][1]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[0][1]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-3" data-row="1" data-col="3">
                <textarea cols="25" rows="4" autocomplete="off" name="question[0][2]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[0][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][0][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][0][2]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[0][2]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[0][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][0][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][0][2]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[0][2]" type="number" class="points" hidden/>
                </div>
            </div>

            <div class="cell-group-4" data-row="1" data-col="4">
                <textarea cols="25" rows="4" autocomplete="off" name="question[0][3]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[0][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][0][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][0][3]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[0][3]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[0][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][0][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][0][3]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[0][3]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-5" data-row="1" data-col="5">
                <textarea cols="25" rows="4" autocomplete="off" name="question[0][4]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[0][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][0][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][0][4]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[0][4]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[0][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][0][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][0][4]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[0][4]" type="number" class="points" hidden />
                </div>
            </div>

            <!-- points -->
            <div class="points-cell-2" data-row="2" data-col="0">
                <input autocomplete="off" name="points[1][0]" type="number" class="points" value="<?php if ($missing || $errors) {
                    echo htmlentities($_POST['points'][1][0]);
                } else {
                    echo '200';
                } ?>" />
            </div>

            <div class="cell-group-6" data-row="2" data-col="1">
                <textarea cols="25" rows="4" autocomplete="off" name="question[1][0]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[1][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][1][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][1][0]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[1][0]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[1][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][1][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][1][0]);
                    } ?></textarea>
            </div>

            <div class="cell-group-7" data-row="2" data-col="2">
                <textarea cols="25" rows="4" autocomplete="off" name="question[1][1]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[1][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][1][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][1][1]);
                    } ?></textarea>
                <textarea cols="25" rows="4"v autocomplete="off" name="answer[1][1]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[1][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][1][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][1][1]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[1][1]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-8" data-row="2" data-col="3">
                <textarea cols="25" rows="4" autocomplete="off" name="question[1][2]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[1][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][1][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][1][2]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[1][2]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[1][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][1][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][1][2]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[1][2]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-9" data-row="2" data-col="4">
                <textarea cols="25" rows="4" autocomplete="off" name="question[1][3]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[1][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][1][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][1][3]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[1][3]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[1][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][1][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][1][3]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[1][3]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-10" data-row="2" data-col="5">
                <textarea cols="25" rows="4" autocomplete="off" name="question[1][4]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[1][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][1][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][1][4]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[1][4]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[1][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][1][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][1][4]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[1][4]" type="number" class="points" hidden />
                </div>
            </div>

            <!-- points -->
            <div class="points-cell-3" data-row="3" data-col="0">
                <input autocomplete="off" name="points[2][0]" type="number" class="points" value="<?php if ($missing || $errors) {
                    echo htmlentities($_POST['points'][2][0]);
                } else {
                    echo '300';
                } ?>" />
            </div>

            <div class="cell-group-11" data-row="3" data-col="1">
                <textarea cols="25" rows="4" autocomplete="off" name="question[2][0]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[2][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][2][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][2][0]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[2][0]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[2][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][2][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][2][0]);
                    } ?></textarea>

            </div>

            <div class="cell-group-12" data-row="3" data-col="2">
                <textarea cols="25" rows="4" autocomplete="off" name="question[2][1]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[2][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][2][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][2][1]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[2][1]" class="answer-holder"placeholder="Enter response in the form of a question" id="answer[2][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][2][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][2][1]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[2][1]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-13" data-row="3" data-col="3">
                <textarea cols="25" rows="4" autocomplete="off" name="question[2][2]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[2][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][2][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][2][2]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[2][2]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[2][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][2][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][2][2]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[2][2]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-14" data-row="3" data-col="4">
                <textarea cols="25" rows="4" autocomplete="off" name="question[2][3]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[2][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][2][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][2][3]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[2][3]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[2][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][2][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][2][3]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[2][3]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-15" data-row="3" data-col="5">
                <textarea cols="25" rows="4" autocomplete="off" name="question[2][4]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[2][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][2][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][2][4]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[2][4]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[2][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][2][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][2][4]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[2][4]" type="number" class="points" hidden />
                </div>
            </div>

            <!-- points -->
            <div class="points-cell-4" data-row="4" data-col="0">
                <input autocomplete="off" name="points[3][0]" type="number" class="points" value="<?php if ($missing || $errors) {
                    echo htmlentities($_POST['points'][3][0]);
                } else {
                    echo '400';
                } ?>" />
            </div>

            <div class="cell-group-16" data-row="4" data-col="1">
                <textarea cols="25" rows="4" autocomplete="off" name="question[3][0]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[3][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][3][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][3][0]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[3][0]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[3][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][3][0] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][3][0]);
                    } ?></textarea>

            </div>

            <div class="cell-group-17" data-row="4" data-col="2">
                <textarea cols="25" rows="4" autocomplete="off" name="question[3][1]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[3][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][3][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][3][1]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[3][1]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[3][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][3][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][3][1]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[3][1]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-18" data-row="4" data-col="3">
                <textarea cols="25" rows="4" autocomplete="off" name="question[3][2]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[3][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][3][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][3][2]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[3][2]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[3][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][3][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][3][2]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[3][2]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-19" data-row="4" data-col="4">
                <textarea cols="25" rows="4" autocomplete="off" name="question[3][3]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[3][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][3][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][3][3]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[3][3]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[3][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][3][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][3][3]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[3][3]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-20" data-row="4" data-col="5">
                <textarea cols="25" rows="4" autocomplete="off" name="question[3][4]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[3][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][3][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][3][4]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[3][4]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[3][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][3][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][3][4]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[3][4]" type="number" class="points" hidden />
                </div>
            </div>

            <!-- points -->
            <div class="points-cell-5" data-row="5" data-col="0">
                <input autocomplete="off" name="points[4][0]" type="number" class="points" value="<?php if ($missing || $errors) {
                    echo htmlentities($_POST['points'][4][0]);
                } else {
                    echo '500';
                } ?>" />
            </div>

            <div class="cell-group-21" data-row="5" data-col="1">
                <textarea cols="25" rows="4" autocomplete="off" name="question[4][0]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[4][0]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][4][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][4][0]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[4][0]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[4][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][4][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][4][0]);
                    } ?></textarea>


            </div>

            <div class="cell-group-22" data-row="5" data-col="2">
                <textarea cols="25" rows="4" autocomplete="off" name="question[4][1]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[4][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][4][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][4][1]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[4][1]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[4][1]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][4][1] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][4][1]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[4][1]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-23" data-row="5" data-col="3">
                <textarea cols="25" rows="4" autocomplete="off" name="question[4][2]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[4][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][4][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][4][2]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[4][2]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[4][2]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][4][2] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][4][2]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[4][2]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-24" data-row="5" data-col="4">
                <textarea cols="25" rows="4" autocomplete="off" name="question[4][3]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[4][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][4][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][4][3]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[4][3]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[4][3]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][4][3] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][4][3]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[4][3]" type="number" class="points" hidden />
                </div>
            </div>

            <div class="cell-group-25" data-row="5" data-col="5">
                <textarea cols="25" rows="4" autocomplete="off" name="question[4][4]" class="question-holder" placeholder="Enter question in the form of a statement" id="question[4][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['question'][4][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['question'][4][4]);
                    } ?></textarea>
                <textarea cols="25" rows="4" autocomplete="off" name="answer[4][4]" class="answer-holder" placeholder="Enter response in the form of a question" id="answer[4][4]" oninput="noStyle(this.id)"
                    <?php if (($missing) && ($_POST['answer'][4][4] == '')) { ?> style="border: 2px solid red;" <?php } ?>><?php if ($missing || $errors) {
                        echo htmlentities($_POST['answer'][4][4]);
                    } ?></textarea>

                <!-- points -->
                <div class="points-cell">
                    <input autocomplete="off" name="points[4][4]" type="number" class="points" hidden />
                </div>
            </div>




        <input name="save" id="save" type="submit" value="Save board">

    </form>

</div>

<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="gameCreate.php" title="Browse games">Browse</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
    </ul>
    <script src="scripts/noStyle.js"></script>
</footer>
</body>
</html>