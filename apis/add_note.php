<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['user_id'])) {
        $userid = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        // التحقق مما إذا كان المعرف المرسل موجود في جدول المستخدمين
        $check_stmt = $con->prepare('SELECT id FROM users WHERE id = ?');
        $check_stmt->execute(array($userid));

        if ($check_stmt->rowCount() > 0) {
            // إذا كان المعرف المستخدم موجودًا، قم بإدراج الملاحظة
            $body = htmlspecialchars(strip_tags($_POST['body']));
            $title = htmlspecialchars(strip_tags($_POST['title']));

            // التحقق مما إذا تم تحميل الصورة
            if (isset($_FILES['note_img'])) {
                $note_img_array = $_FILES['note_img'];
                $size = $note_img_array['size'];
                $temp_name = $note_img_array['tmp_name'];
                $name = $note_img_array['name'];
                $type = pathinfo($name, PATHINFO_EXTENSION);
                $allowedImageTypes = array("jpg", "jpeg", "png", "gif");
                $imgerror = array();

                // التحقق من أن حجم الصورة لا يتجاوز 4 ميجابايت
                if ($size > 4194304) {
                    $imgerror[] = 'حجم الصورة أكبر من 4 ميجابايت ';
                }
                // التحقق من نوع الصورة
                if (!in_array($type, $allowedImageTypes)) {
                    $imgerror[] = 'نوع الصورة غير مسموح به ';
                }

                if (!empty($imgerror)) {
                    // إرسال استجابة JSON في حالة وجود أخطاء
                    echo json_encode(array('status' => false, 'errors' => $imgerror));
                    http_response_code(400); // Bad request
                    exit; // توقف التنفيذ
                } else {
                    // نقل الصورة المحملة إلى المجلد المطلوب
                    $note_image = rand(0, 100000) . '_' . $name;
                    move_uploaded_file($temp_name, "uploads\Images\\" . $note_image);
                }
            } else {
                // إذا لم يتم تحميل أي صورة، ضع $note_image فارغة
                $note_image = '';
            }

            // إدراج الملاحظة إلى قاعدة البيانات
            $stmt = $con->prepare('INSERT INTO notes(title, body, user_id, img) VALUES(?, ?, ?, ?)');
            $stmt->execute(array($title, $body, $userid, $note_image));

            if ($stmt->rowCount() > 0) {
                echo json_encode(array('status' => true, 'msg' => 'تم'));
                http_response_code(200);
            } else {
                echo json_encode(array('status' => false, 'msg' => 'لم يتم الإضافة'));
                http_response_code(404);
            }
        } else {
            // إذا لم يكن المعرف المرسل موجودًا، قم بإرجاع استجابة خطأ
            echo json_encode(array('status' => false, 'msg' => 'المستخدم غير موجود'));
            http_response_code(404);
        }
    }
}
