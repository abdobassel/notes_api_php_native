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
        $stmt = $con->prepare('INSERT INTO users(Username,Email,Password) VALUES(?, ?, ?)');
        $stmt->execute(array($username, $email, $hash));
        $count = $stmt->rowCount();

        $userInfo[] = array(
            'username' => $username,
            'email' => $email,
        );


        if ($count > 0) {
            http_response_code(200);
            $response = json_encode(array('status' => true, 'message' => 'success', 'info' => $userInfo));
        }
    } elseif (empty($username) || empty($email)) {
        $response = json_encode(array('status' => false, 'message' => 'Enter both username and email'));
    } elseif (empty($username)) {
        $response = json_encode(array('status' => false, 'message' => 'Username is empty'));
    } elseif (empty($email)) {
        $response = json_encode(array('status' => false, 'message' => 'Email is empty'));
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = json_encode(array('status' => false, 'message' => 'Invalid email address'));
    } else {
        $response = json_encode(array('status' => false, 'message' => 'Failed registration'));
    }


    echo $response;
    exit();
}
