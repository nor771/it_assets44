<?php
// يجب دائماً تضمين ملف الإعدادات أولاً
require_once "../config.php";

// التحقق من وجود معرف الجهاز في الرابط
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // إعداد استعلام الحذف الآمن
    $sql = "DELETE FROM computers WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            // في حالة النجاح، العودة إلى صفحة القائمة
            header("location: index.php");
            exit();
        } else {
            // في حالة فشل الاستعلام، يمكنك عرض رسالة خطأ
            echo "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
        }
        $stmt->close();
    }
    $conn->close();
} else {
    // إذا لم يكن هناك معرف، العودة إلى صفحة القائمة
    header("location: index.php");
    exit();
}
?>