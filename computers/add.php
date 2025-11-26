<?php
require_once "../config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // جلب البيانات من النموذج وتنظيفها
    $asset_tag = sanitize_input($_POST["asset_tag"]);
    $type = sanitize_input($_POST["type"]);
    $make = sanitize_input($_POST["make"]);
    $model = sanitize_input($_POST["model"]);
    $serial_number = sanitize_input($_POST["serial_number"]);
    $cpu = sanitize_input($_POST["cpu"]);
    $ram_gb = sanitize_input($_POST["ram_gb"]);
    $storage_type = sanitize_input($_POST["storage_type"]);
    $storage_gb = sanitize_input($_POST["storage_gb"]);
    $os = sanitize_input($_POST["os"]);
    $location = sanitize_input($_POST["location"]);
    $status = sanitize_input($_POST["status"]);
    $assigned_to = sanitize_input($_POST["assigned_to"]);
    $purchase_date = sanitize_input($_POST["purchase_date"]);
    $warranty_expiry = sanitize_input($_POST["warranty_expiry"]);
    $notes = sanitize_input($_POST["notes"]);

    // جملة SQL المُصححة (16 عمود و 16 قيمة)
    $sql = "INSERT INTO computers (asset_tag, type, make, model, serial_number, cpu, ram_gb, storage_type, storage_gb, os, location, status, assigned_to, purchase_date, warranty_expiry, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if($stmt = $conn->prepare($sql)){
        // دالة bind_param المُصححة (s للنص, i للرقم)
        $stmt->bind_param("ssssssiissssssss", $asset_tag, $type, $make, $model, $serial_number, $cpu, $ram_gb, $storage_type, $storage_gb, $os, $location, $status, $assigned_to, $purchase_date, $warranty_expiry, $notes);
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
    <title>إضافة جهاز حاسب جديد</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="../index.php">الرئيسية</a>
                <a href="index.php">قائمة أجهزة الحاسب</a>
                <a href="../logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <div class="form-container">
            <h2><i class="fas fa-plus-circle"></i> إضافة جهاز حاسب جديد</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group"><label>الرقم التعريفي للأصل</label><input type="text" name="asset_tag" required></div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" required>
                        <option value="Desktop">كمبيوتر مكتبي</option>
                        <option value="Laptop">لابتوب</option>
                        <option value="All-in-One">All-in-One</option>
                    </select>
                </div>
                <div class="form-group"><label>الشركة المصنعة</label><input type="text" name="make" required></div>
                <div class="form-group"><label>الموديل</label><input type="text" name="model" required></div>
                <div class="form-group"><label>الرقم التسلسلي</label><input type="text" name="serial_number" required></div>
                <div class="form-group"><label>معالج (CPU)</label><input type="text" name="cpu" required></div>
                <div class="form-group"><label>الذاكرة العشوائية (RAM GB)</label><input type="number" name="ram_gb" required></div>
                <div class="form-group">
                    <label>نوع التخزين</label>
                    <select name="storage_type" required>
                        <option value="SSD">SSD</option>
                        <option value="HDD">HDD</option>
                    </select>
                </div>
                <div class="form-group"><label>سعة التخزين (GB)</label><input type="number" name="storage_gb" required></div>
                <div class="form-group"><label>نظام التشغيل</label><input type="text" name="os" required></div>
                <div class="form-group"><label>الموقع</label><input type="text" name="location" required></div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="قيد الاستخدام">قيد الاستخدام</option>
                        <option value="تحت الصيانة">تحت الصيانة</option>
                        <option value="معطل">معطل</option>
                        <option value="مخزن">مخزن</option>
                    </select>
                </div>
                <div class="form-group"><label>مسند إلى</label><input type="text" name="assigned_to"></div>
                <div class="form-group"><label>تاريخ الشراء</label><input type="date" name="purchase_date"></div>
                <div class="form-group"><label>تاريخ انتهاء الضمان</label><input type="date" name="warranty_expiry"></div>
                <div class="form-group"><label>ملاحظات</label><textarea name="notes" rows="3"></textarea></div>
                
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