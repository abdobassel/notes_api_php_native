<?php

include '../init.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {



    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);


    $pass = $_POST['password'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!empty($pass)) {
        $hash = sha1($pass);
    }


    $check = checkItem('Username', 'users', $username);
    $check2 = checkItem('Email', 'users', $email);



    if ($check == 0 && $check2 == 0 && !empty($username) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $con->prepare('INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)');
        $stmt->execute(array($username, $email, $hash));

        $userInfo[] = array(

            'username' => $username,
            'email' => $email,
        );
        $response = array('status' => true, 'Message' => 'Done Success', 'info' => $userInfo);
        $_SESSION['username'] = $username;
        print_r($_SESSION);
    } elseif (empty($username) && empty($email)) {
        $response = array('status' => false, 'Message' => 'enter username and email ');
    } elseif (empty($username)) {
        $response = array('status' => false, 'Message' => 'username is empty try again');
    } elseif (empty($email)) {
        $response = array('status' => false, 'Message' => 'email is empty');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array('status' => false, 'message' => 'Invalid email address');
    } else {
        $response = array('status' => false, 'message' => 'failed register');
    }

    echo json_encode($response);
    header('Content-Type: application/json');
    exit();
}
