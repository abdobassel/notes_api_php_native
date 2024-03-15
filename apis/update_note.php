<?php


include '../init.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['note_id'])) {
        $noteid = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
    }
    $body = filter_var($_POST['body'], FILTER_SANITIZE_SPECIAL_CHARS);
    $title = htmlspecialchars(strip_tags($_POST['title']));

    $checkNote = checkItem('id', 'notes', $noteid);

    if ($checkNote > 0 && !empty($body) && !empty($title)) {

        $stmt = $con->prepare("UPDATE notes SET title = ?,body = ? WHERE id = ?");
        $stmt->execute(array($title, $body, $noteid));

        if ($stmt->rowCount() > 0) {


            echo json_encode(array('status' => true, 'msg' => 'Note Updated'));
            http_response_code(200);
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed Update'));
            http_response_code(400);
        }
    } else {
        echo json_encode(array('status' => false, 'msg' => 'No Such ID'));
        http_response_code(404);
    }
} else {
    echo json_encode(array('status' => false, 'msg' => 'Bad Request'));
    http_response_code(400);
}
