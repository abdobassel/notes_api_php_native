<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['note_id'])) {
        $noteid = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
    }
    $body = htmlspecialchars(strip_tags($_POST['body']));
    $title = htmlspecialchars(strip_tags($_POST['title']));

    // التحقق مما إذا كان الـ note_id موجود والبيانات غير فارغة
    if ($noteid && !empty($body) && !empty($title)) {
        // تحديث البيانات في قاعدة البيانات
        $stmt = $con->prepare("UPDATE notes SET title = ?, body = ? WHERE id = ?");
        $stmt->execute([$title, $body, $noteid]);

        // التحقق مما إذا كان هناك صفوف تم تحديثها
        if ($stmt->rowCount() > 0) {
            // إذا تم تحديث البيانات بنجاح
            echo json_encode(array('status' => true, 'msg' => 'Note Updated'));
            http_response_code(200);
        } else {
            // إذا لم يتم تحديث أي بيانات
            echo json_encode(array('status' => false, 'msg' => 'Failed Update'));
            http_response_code(400);
        }
    } else {
        // إذا كان أحد البيانات غير متوفرة
        echo json_encode(array('status' => false, 'msg' => 'Invalid Data'));
        http_response_code(400);
    }
} else {
    // إذا كان الطلب غير صالح
    echo json_encode(array('status' => false, 'msg' => 'Bad Request'));
    http_response_code(400);
}
