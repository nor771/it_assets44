<?php
// ==========================================
// إعدادات الجلسة الآمنة - يجب أن تكون في الأعلى وقبل أي شيء آخر
// ==========================================

// منع الوصول إلى ملفات تعريف الارتباط عبر JavaScript (يمنع هجمات XSS)
ini_set('session.cookie_httponly', 1);

// التأكد من أن ملفات تعريف الارتباط تُستخدم فقط لنقل معرف الجلسة
ini_set('session.use_only_cookies', 1);

// إرسال ملفات تعريف الارتباط عبر اتصال آمن (HTTPS) فقط
// ملاحظة: هذا الشرط مهم ليعمل على سيرفر محلي (XAMPP) الذي يستخدم HTTP
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// بدء الجلسة - الآن يتم هنا مركزياً في كل مرة يتم فيها تضمين هذا الملف
session_start();

// ==========================================
// إعدادات الاتصال بقاعدة البيانات
// ==========================================
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'digital_college_it');

 $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conn === false){
    die("خطأ: لم يتمكن من الاتصال بقاعدة البيانات. " . $conn->connect_error);
}

// إضافة مجموعة الأحرف لقاعدة البيانات
 $conn->set_charset("utf8mb4");

// ==========================================
// دوال مساعدة (لمنع تكرار الكود)
// ==========================================
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_admin() {
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === 'admin';
}

function redirect_if_not_logged_in($location = "login.php") {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: $location");
        exit;
    }
}

function get_asset_count($conn, $table) {
    $sql = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['count'];
}

function get_asset_status_counts($conn, $table) {
    $status_counts = ['working' => 0, 'broken' => 0, 'maintenance' => 0];
    $sql = "SELECT status, COUNT(*) as count FROM $table GROUP BY status";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        if(isset($status_counts[$row['status']])){
            $status_counts[$row['status']] = $row['count'];
        }
    }
    return $status_counts;
}
?>