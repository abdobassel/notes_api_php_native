<?php

include '../init.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['user_id'])) {
        $userid = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        // Check if the user_id exists in the users table
        $check_stmt = $con->prepare('SELECT id FROM users WHERE id = ?');
        $check_stmt->execute(array($userid));

        if ($check_stmt->rowCount() > 0) {
            // If user_id exists, proceed with inserting the note
            $body = htmlspecialchars(strip_tags($_POST['body']));
            $title = htmlspecialchars(strip_tags($_POST['title']));

            $stmt = $con->prepare('INSERT INTO notes(title, body, user_id) VALUES(?, ?, ?)');
            $stmt->execute(array($title, $body, $userid));

            if ($stmt->rowCount() > 0) {
                echo json_encode(array('status' => true, 'msg' => 'done'));
                http_response_code(200);
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Not add'));
                http_response_code(404);
            }
        } else {
            // If user_id doesn't exist, return error response
            echo json_encode(array('status' => false, 'msg' => 'User not found'));
            http_response_code(404);
        }
    }
}
