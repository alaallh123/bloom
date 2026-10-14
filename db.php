
<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloom_db";

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($host, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// ضبط الترميز للغة العربية
$conn->set_charset("utf8mb4");
?>