<?php


if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['pwd']);
    $retyped = trim($_POST['conf_pwd']);
    $email = trim($_POST['email']);
    require_once '../includes/register_user_mysqli.php';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CITY Jeopardy Sign Up</title>
    <link rel="stylesheet" href="../styles/mystyle.css">
</head>
<body>
<header>
    <header id="header" class="group" role="banner">
        <a href="../home.php" title="Return to the front page">
            <img src="../images/CITY-College-Sheffield.jpg" alt="CITY Jeopardy" />
        </a>
        <p id="motto">Welcome to <span class="logo">CITY Jeopardy!</span><br>The fun way to learn</p>
        <ul class="loginbtn">
            <li>
                <a href="login.php">Login</a>
            </li>
        </ul>
    </header>
</header>
<div id="main" class="group">
    <div class="register">
        <h1>Register user</h1>
        <?php
        if (isset($success)) {
            echo "<p>$success</p>";
        } elseif (isset($errors) && !empty($errors)) {
            echo '<ul>';
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul>';
        }
        ?>
        <form method="post" action="">
            <ul>
                <li class="form-row">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </li>
                <li class="form-row">
                    <label for="pwd">Password:</label>
                    <input type="password" name="pwd" id="pwd" required>
                </li>
                <li class="form-row">
                    <label for="conf_pwd">Confirm password:</label>
                    <input type="password" name="conf_pwd" id="conf_pwd" required>
                </li>
                <li class="form-row">
                    <label for="email">E-Mail:</label>
                    <input type="email" name="email" id="email" required>
                </li>
                <li>
                    <input name="register" type="submit" id="register" value="Register">
                </li>
                <li>
                    Already have an account?<br>
                    <a href="login.php">Login</a>
                </li>
            </ul>
        </form>
    </div>
</div>
<footer id="footer" role="contentinfo">
    <ul>
        <li>
            <a href="../gameCreate.php" title="Create a game">Create</a>
        </li>
        <li>
            <a href="#" title="About">About</a>
        </li>
        <li>
            <a href="login.php" title="Login">Login</a>
        </li>
    </ul>
</footer>
</body>
</html>