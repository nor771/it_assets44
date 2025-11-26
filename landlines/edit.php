<?php
require_once "../config.php";

// تهيئة المتغيرات في بداية الصفحة لتجنب خطأ "Undefined variable"
 $phone_number = $department = $status = $location = "";

// جلب بيانات الهاتف المحدد للتعديل
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    $sql = "SELECT * FROM landlines WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $phone_number = $row['phone_number'];
                $department = $row['department'];
                $status = $row['status'];
                $location = $row['location'];
            } else {
                header("location: index.php");
                exit();
            }
        }
        $stmt->close();
    }
} else {
    header("location: index.php");
    exit();
}

// معالجة إرسال نموذج التعديل
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $phone_number = sanitize_input($_POST["phone_number"]);
    $department = sanitize_input($_POST["department"]);
    $status = sanitize_input($_POST["status"]);
    $location = sanitize_input($_POST["location"]);

    $sql = "UPDATE landlines SET phone_number=?, department=?, status=?, location=? WHERE id=?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ssssi", $phone_number, $department, $status, $location, $id);
        if($stmt->execute()){
            header("location: index.php");
            exit();
        } else {
            echo "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات الهاتف الأرضي</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="../index.php">الرئيسية</a>
                <a href="index.php">قائمة الهواتف الأرضية</a>
                <a href="../logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <div class="form-container">
            <h2><i class="fas fa-edit"></i> تعديل بيانات الهاتف الأرضي</h2>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                <div class="form-group"><label>رقم الهاتف</label><input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required></div>
                <div class="form-group"><label>القسم</label><input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>" required></div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="يعمل" <?php if($status == 'يعمل') echo 'selected'; ?>>يعمل</option>
                        <option value="معطل" <?php if($status == 'معطل') echo 'selected'; ?>>معطل</option>
                    </select>
                </div>
                <div class="form-group"><label>الموقع</label><input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required></div>
                
                <!-- ===== الأزرار الجديدة والمُحسّنة ===== -->
                <div class="form-group" style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="submit" class="btn-glossy-light">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                    <a href="index.php" class="btn-glossy-white">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </main>
    <script src="../script.js"></script>
</body>
</html>