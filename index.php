<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // التحقق من وجود المتغيرات في الطلب
    if (isset($_GET['password']) && isset($_GET['email'])) {
        $pass = sha1($_GET['password']);
        $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

        // التحقق من صحة عنوان البريد الإلكتروني
        if (!$email) {
            http_response_code(400); // الطلب غير صالح
            echo json_encode(array('error' => 'Invalid email address'));
            exit();
        }

        $stmt = $con->prepare('SELECT * FROM users WHERE Email = ?');
        $stmt->execute(array($email));

        // التحقق من أن هناك بيانات تم استرجاعها
        if ($stmt->rowCount() > 0) {
            $userInfo =  $stmt->fetch(PDO::FETCH_ASSOC);
            // التحقق من صحة كلمة المرور
            if ($userInfo['Password'] === $pass) {
                $data = array(
                    'id' => $userInfo['id'],
                    'username' => $userInfo['Username'],
                    'email' => $userInfo['Email']
                );
                echo json_encode(array('userinfo' => $data));
            } else {
                // كلمة المرور غير صحيحة
                echo json_encode(array('error' => 'Incorrect password'));
            }
        } else {
            // المستخدم غير موجود
            echo json_encode(array('error' => 'User not found'));
        }

        // تحديد نوع المحتوى ليكون JSON
        header('Content-Type: application/json');
        exit();
    } else {
        http_response_code(400); // الطلب غير صالح
        echo json_encode(array('error' => 'Missing parameters'));
        exit();
    }
}
