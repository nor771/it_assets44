<?php
require_once "../config.php";

// جلب بيانات الطابعة المحددة للتعديل
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    $sql = "SELECT * FROM printers WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $asset_tag = $row['asset_tag'];
                $make = $row['make'];
                $model = $row['model'];
                $serial_number = $row['serial_number'];
                $type = $row['type'];
                $location = $row['location'];
                $status = $row['status'];
                $ip_address = $row['ip_address'];
                $purchase_date = $row['purchase_date'];
                $notes = $row['notes'];
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

    $sql = "UPDATE printers SET asset_tag=?, make=?, model=?, serial_number=?, type=?, location=?, status=?, ip_address=?, purchase_date=?, notes=? WHERE id=?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ssssssssssi", $asset_tag, $make, $model, $serial_number, $type, $location, $status, $ip_address, $purchase_date, $notes, $id);
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
    <title>تعديل بيانات الطابعة</title>
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
            <h2><i class="fas fa-edit"></i> تعديل بيانات الطابعة</h2>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                <div class="form-group"><label>الرقم التعريفي للأصل</label><input type="text" name="asset_tag" value="<?php echo htmlspecialchars($asset_tag); ?>" required></div>
                <div class="form-group"><label>الشركة المصنعة</label><input type="text" name="make" value="<?php echo htmlspecialchars($make); ?>" required></div>
                <div class="form-group"><label>الموديل</label><input type="text" name="model" value="<?php echo htmlspecialchars($model); ?>" required></div>
                <div class="form-group"><label>الرقم التسلسلي</label><input type="text" name="serial_number" value="<?php echo htmlspecialchars($serial_number); ?>" required></div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" required>
                        <option value="Laser" <?php if($type == 'Laser') echo 'selected'; ?>>Laser</option>
                        <option value="Inkjet" <?php if($type == 'Inkjet') echo 'selected'; ?>>Inkjet</option>
                        <option value="Multifunction" <?php if($type == 'Multifunction') echo 'selected'; ?>>Multifunction</option>
                    </select>
                </div>
                <div class="form-group"><label>الموقع</label><input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required></div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="يعمل" <?php if($status == 'يعمل') echo 'selected'; ?>>يعمل</option>
                        <option value="تحتاج حبر/حبر نافذ" <?php if($status == 'تحتاج حبر/حبر نافذ') echo 'selected'; ?>>تحتاج حبر/حبر نافذ</option>
                        <option value="تحت الصيانة" <?php if($status == 'تحت الصيانة') echo 'selected'; ?>>تحت الصيانة</option>
                        <option value="معطل" <?php if($status == 'معطل') echo 'selected'; ?>>معطل</option>
                    </select>
                </div>
                <div class="form-group"><label>عنوان IP</label><input type="text" name="ip_address" value="<?php echo htmlspecialchars($ip_address); ?>"></div>
                <div class="form-group"><label>تاريخ الشراء</label><input type="date" name="purchase_date" value="<?php echo htmlspecialchars($purchase_date); ?>"></div>
                <div class="form-group"><label>ملاحظات</label><textarea name="notes" rows="3"><?php echo htmlspecialchars($notes); ?></textarea></div>
                
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