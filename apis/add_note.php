<?php
include '../init.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['user_id'])) {
        $userid = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    }
    $body = filter_var($_POST['body'], FILTER_SANITIZE_SPECIAL_CHARS);
    $title = htmlspecialchars(strip_tags($_POST['title']));

    $stmt = $con->prepare('INSERT INTO notes (title, body, user_id) VALUES (?, ?, ?)');
    $stmt->execute(array($title, $body, $userid));

    if ($stmt->rowCount() > 0) {


        echo json_encode(array('status' => true, 'msg' => 'done'));
        http_response_code(200);
    } else {
        echo json_encode(array('status' => false, 'msg' => 'Not add'));
        http_response_code(404);
    }
}
