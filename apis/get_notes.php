<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // التحقق من وجود معرف المستخدم
    if (isset($_POST['user_id'])) {
        $userid = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        $stm = $con->prepare("SELECT * FROM notes WHERE user_id = ?");
        $stm->execute(array($userid));

        // التحقق من وجود ملاحظات للمستخدم
        if ($stm->rowCount() > 0) {
            // استرداد كل الصفوف المتطابقة
            $data =  $stm->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('status' => true, 'notes' => $data));
            http_response_code(200);
        } else {
            // رسالة الخطأ إذا لم يتم العثور على ملاحظات
            echo json_encode(array('status' => false, 'message' => 'No notes found for the user'));
            http_response_code(404);
        }
    } else {
        // رسالة الخطأ إذا كان معرف المستخدم غير موجود
        echo json_encode(array('status' => false, 'message' => 'User ID is missing'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'method valid'));
}
