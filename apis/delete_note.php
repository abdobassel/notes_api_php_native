<?php


include '../init.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['note_id'])) {
        $noteid = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
    }

    $checkNote = checkItem('id', 'notes', $noteid);

    if ($checkNote > 0) {
        $stmt = $con->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->execute(array($noteid));

        if ($stmt->rowCount() > 0) {


            echo json_encode(array('status' => true, 'msg' => 'Note Deleted'));
            http_response_code(200);
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed Delete'));
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
