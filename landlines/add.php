<?php
require_once "../config.php";

// تهيئة المتغيرات في بداية الصفحة لتجنب تحذير "Undefined variable"
 $phone_number = $department = $status = $location = "";
 $phone_number_err = ""; // متغير لتخزين رسالة الخطأ

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // جلب البيانات من النموذج وتنظيفها
    $phone_number = sanitize_input($_POST["phone_number"]);
    $department = sanitize_input($_POST["department"]);
    $status = sanitize_input($_POST["status"]);
    $location = sanitize_input($_POST["location"]);

    // التحقق أولاً مما إذا كان رقم الهاتف موجوداً بالفعل
    $check_sql = "SELECT id FROM landlines WHERE phone_number = ?";
    if($check_stmt = $conn->prepare($check_sql)){
        $check_stmt->bind_param("s", $phone_number);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if($check_stmt->num_rows > 0){
            // إذا كان الرقم موجوداً، قم بتخزين رسالة خطأ
            $phone_number_err = "هذا الرقم مسجل بالفعل في النظام. الرجاء استخدام رقم آخر.";
        } else {
            // إذا كان الرقم غير موجود، قم بعملية الإدخال
            $sql = "INSERT INTO landlines (phone_number, department, status, location) VALUES (?, ?, ?, ?)";
            
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("ssss", $phone_number, $department, $status, $location);
                if($stmt->execute()){
                    header("location: index.php");
                    exit();
                } else {
                    echo "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
                }
                $stmt->close();
            }
        }
        $check_stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة هاتف أرضي جديد</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
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
            <h2><i class="fas fa-plus-circle"></i> إضافة هاتف أرضي جديد</h2>
            
            <?php 
            // عرض رسالة الخطأ إذا كانت موجودة
            if(!empty($phone_number_err)){
                echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i> ' . $phone_number_err . '</div>';
            }
            ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                </div>
                <div class="form-group">
                    <label>القسم</label>
                    <input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>" required>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="يعمل" <?php if($status == 'يعمل') echo 'selected'; ?>>يعمل</option>
                        <option value="معطل" <?php if($status == 'معطل') echo 'selected'; ?>>معطل</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الموقع</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                </div>
                
                <!-- ===== الأزرار الجديدة والمُحسّنة ===== -->
                <div class="form-group" style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="submit" class="btn-glossy-light">
                        <i class="fas fa-save"></i> حفظ
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