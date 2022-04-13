<?php
    use Authenticate\CheckPassword;
    require_once __DIR__ . '/../Authenticate/CheckPassword.php';
    $usernameMinChars = 6;
    $errors = [];
    if (strlen($username) < $usernameMinChars) {
        $errors[] = "Username must be at least $usernameMinChars characters.";
    }
    if (preg_match('/\s/', $username)) {
        $errors[] = 'Username should not contain spaces.';
    }
    $checkPwd = new CheckPassword($password, 10);
    $checkPwd->requireMixedCase();
    $checkPwd->requireNumbers(2);
    $checkPwd->requireSymbols();
    $passwordOK = $checkPwd->check();
    if (!$passwordOK) {
        $errors = array_merge($errors, $checkPwd->getErrors());
    }
    if ($password != $retyped) {
        $errors[] = "Your passwords don't match.";
    }
    if (!preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^', $email)) {
        $errors[] = "Email is not valid.";
    }
    if (!$errors) {
        // encrypt password using default encryption
        //$password = password_hash($password, PASSWORD_DEFAULT);
        //include the connection file
        require_once 'connection.php';
        $conn = dbConnect('write');
        // prepare SQL statement
        $sql = 'INSERT INTO Creator (username, password, email) VALUES (?, ?, ?)';
        $stmt = $conn->stmt_init();
        if ($stmt = $conn->prepare($sql)) {
            // bind parameters and insert the details into the database
            $stmt->bind_param('sss', $username, $password, $email);
            $stmt->execute();
        }
        if ($stmt->affected_rows == 1) {
            $success = "$username has been registered. You may now log in.";
        } elseif ($stmt->errno == 1062) {
            $errors[] = "$username is already in use. Please choose another username.";
        } else {
            $errors[] = $stmt->error;
        }
    }
?>