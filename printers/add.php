<?php
require_once "../config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $asset_tag = sanitize_input($_POST["asset_tag"]);
    $make = sanitize_input($_POST["make"]);
    $model = sanitize_input($_POST["model"]);
    $serial_number = sanitize_input($_POST["serial_number"]);
    $type = sanitize_input($_POST["type"]);
    $location = sanitize_input($_POST["location"]);
    $status = sanitize_input($_POST["status"]);
    $ip_address = sanitize_input($_POST["ip_address"]);
    $purchase_date = sanitize_input($_POST["purchase_date"]);
    $notes = sanitize_input($_POST["notes"]);

   $sql = "INSERT INTO printers (asset_tag, make, model, serial_number, type, location, status, ip_address, purchase_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if($stmt = $conn->prepare($sql)){
     $stmt->bind_param("ssssssssss", $asset_tag, $make, $model, $serial_number, $type, $location, $status, $ip_address, $purchase_date, $notes);
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
    <title>إضافة طابعة جديدة</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="../index.php">الرئيسية</a>
                <a href="index.php">قائمة الطابعات</a>
                <a href="../logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <div class="form-container">
            <h2><i class="fas fa-plus-circle"></i> إضافة طابعة جديدة</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group"><label>الرقم التعريفي للأصل</label><input type="text" name="asset_tag" required></div>
                <div class="form-group"><label>الشركة المصنعة</label><input type="text" name="make" required></div>
                <div class="form-group"><label>الموديل</label><input type="text" name="model" required></div>
                <div class="form-group"><label>الرقم التسلسلي</label><input type="text" name="serial_number" required></div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" required>
                        <option value="Laser">Laser</option>
                        <option value="Inkjet">Inkjet</option>
                        <option value="Multifunction">Multifunction</option>
                    </select>
                </div>
                <div class="form-group"><label>الموقع</label><input type="text" name="location" required></div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="يعمل">يعمل</option>
                        <option value="تحتاج حبر/حبر نافذ">تحتاج حبر/حبر نافذ</option>
                        <option value="تحت الصيانة">تحت الصيانة</option>
                        <option value="معطل">معطل</option>
                    </select>
                </div>
                <div class="form-group"><label>عنوان IP</label><input type="text" name="ip_address"></div>
                <div class="form-group"><label>تاريخ الشراء</label><input type="date" name="purchase_date"></div>
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