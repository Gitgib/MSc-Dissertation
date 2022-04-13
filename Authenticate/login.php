<?php
    $error = '';
    if (isset($_POST['login'])) {
        session_start();
        $username = trim($_POST['username']);
        $password = trim($_POST['pwd']);
        //location to redirect on success
        $redirect = 'http://localhost/cityjeopardy/homelogged.php';
        require_once '../includes/authenticate_mysqli.php';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CITY Jeopardy Login</title>
    <link rel="stylesheet" href="../styles/mystyle.css">
</head>
<body>
    <header>
        <header id="header" class="group" role="banner">
            <a href="#" title="Return to the front page">
                <img src="../images/CITY-College-Sheffield.jpg" alt="CITY Jeopardy" />
            </a>
            <p id="motto">Welcome to <span class="logo">CITY Jeopardy!</span><br>The fun way to learn</p>
            <ul>
                <li>
                    <a href="register.php">Sign up</a>
                </li>
            </ul>
        </header>
    </header>
    <div id="main" class="group">
        <div class="login">
            <h3>CITY Jeopardy Login</h3>
            <form method="post" action="">
                <ul class="wrapper">
                    <li class="form-row">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username">
                    </li>
                    <li class="form-row">
                        <label for="pwd">Password:</label>
                        <input type="password" name="pwd" id="pwd">
                    </li>
                    <?php
                    if ($error) {
                        echo "<p>$error</p>";
                    } elseif (isset($_GET['expired'])) {
                        ?>
                        <p>Your session has expired. Please log in again.</p>
                    <?php } ?>
                    <li class="form-row">
                        <input name="login" type="submit" id="login" value="Log in">
                    </li>
                    <li class="form-row">
                        <p>Don't have an account?</p><br>
                        <a href="register.php">Register here!</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <footer id="footer" role="contentinfo">
        <ul>
            <li>
                <a href="#" title="Create a game">Create</a>
            </li>
            <li>
                <a href="#" title="Browse games">Browse</a>
            </li>
            <li>
                <a href="#" title="About">About</a>
            </li>
            <li>
                <a href="login.php" title="Login">Login</a>
            </li>
            <li>
                <a href="Authenticate/register.php" title="Sign up">Sign up</a>
            </li>
        </ul>
    </footer>
</body>
</html>