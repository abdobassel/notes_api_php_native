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

            // Check if an image was uploaded
            if (isset($_FILES['note_img'])) {
                $note_img_array = $_FILES['note_img'];
                $size = $note_img_array['size'];
                $temp_name = $note_img_array['tmp_name'];
                $name = $note_img_array['name'];
                $type = pathinfo($name, PATHINFO_EXTENSION);
                $allowedImageTypes = array("jpg", "jpeg", "png", "gif");
                $imgerror = array();

                // التحقق من أن الصورة من النوع المسموح به
                if ($size > 4194304) {
                    echo "image Size is larger than 4 MB ";
                    $imgerror[] = 'image Size is larger than 4 MB ';
                }
                if (!in_array($type, $allowedImageTypes)) {
                    echo "نوع الصورة غير مسموح به.";
                    $imgerror[] = 'image type not allowed ';
                }

                if (empty($imgerror)) {
                    // Move uploaded image to the desired directory
                    $note_image = rand(0, 100000) . '_' . $name;
                    move_uploaded_file($temp_name, "uploads\Images\\" . $note_image);
                }
            } else {
                // If no image uploaded, set $note_image to empty string
                $note_image = '';
            }

            // Insert note into database
            $stmt = $con->prepare('INSERT INTO notes(title, body, user_id, img) VALUES(?, ?, ?, ?)');
            $stmt->execute(array($title, $body, $userid, $note_image));

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
