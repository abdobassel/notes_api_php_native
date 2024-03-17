<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // التحقق من وجود المتغيرات في الطلب
    if (isset($_POST['password']) && isset($_POST['email'])) {
        $pass = sha1($_POST['password']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        // التحقق من صحة عنوان البريد الإلكتروني
        if (!$email) {
            http_response_code(400); // الطلب غير صالح
            echo json_encode(array('error' => 'Invalid email address'));
            exit();
        }

        $stmt = $con->prepare('SELECT * FROM users WHERE Email = ? AND Password =?');
        $stmt->execute(array($email, $pass));


        // التحقق من أن هناك بيانات تم استرجاعها
        if ($stmt->rowCount() > 0) {
            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            // التحقق من صحة كلمة المرور
            if ($userInfo['Password'] === $pass) {
                $data = array(
                    'id' => $userInfo['id'],
                    'username' => $userInfo['Username'],
                    'email' => $userInfo['Email'],
                );
                $response = json_encode(array('message' => 'success', 'userinfo' => $data));
                $_SESSION['username'] = $userInfo['Username'];
            } else {
                // كلمة المرور غير صحيحة
                $response = json_encode(array('message' => 'Error', 'error' => 'Incorrect password'));
            }
        } else {
            // المستخدم غير موجود
            $response = json_encode(array('message' => 'Error', 'error' => 'User not found'));
        }

        echo $response;
        exit();
    } else {
        http_response_code(400); // الطلب غير صالح
        $response = json_encode(array('message' => 'Error', 'error' => 'Missing parameters'));
        echo $response;
        exit();
    }
}
