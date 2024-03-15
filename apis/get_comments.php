<?php
// استيراد الملف الذي يحتوي على الاتصال بقاعدة البيانات وأي دوال أو متغيرات تحتاج إليها هنا
include '../config.php';

// التحقق من جلسة المستخدم
session_start();
/*
if (!isset($_SESSION['Username'])) {
    header('Location: ../index.php');
    exit();
}

try {
    echo $_SESSION['Username'];
    // تنفيذ الاستعلام لجلب البيانات
    $stmt = $con->prepare("SELECT comments.*, users.Username , items.Name AS item_name
        FROM comments 
        INNER JOIN users ON users.UserID = comments.user_id
        INNER JOIN items ON items.Item_Id = comments.item_id
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    // تنظيف البيانات وتحويلها إلى JSON
    $cleaned_data = array();
    foreach ($rows as $row) {
        $cleaned_data[] = array(
            'comment_id' => $row['comment_id'],
            'body' => $row['body'],
            'date' => $row['date'],
            'Approve' => $row['Approve'],
            'user_id' => $row['user_id'],
            'item_id' => $row['item_id'],
            'Username' => $row['Username'],
            'item_name' => $row['item_name']
        );
    }

    // إرسال البيانات كـ JSON مع حالة النجاح
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'data' => $cleaned_data));
} catch (PDOException $e) {
    // إرسال الخطأ كـ JSON في حالة فشل الاستعلام
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
*/