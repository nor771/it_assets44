<?php
// يجب دائماً تضمين ملف الإعدادات أولاً
require_once "../config.php";

// جلب بيانات الجهاز المحدد للتعديل
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    $sql = "SELECT * FROM computers WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $asset_tag = $row['asset_tag'];
                $type = $row['type'];
                $make = $row['make'];
                $model = $row['model'];
                $serial_number = $row['serial_number'];
                $cpu = $row['cpu'];
                $ram_gb = $row['ram_gb'];
                $storage_type = $row['storage_type'];
                $storage_gb = $row['storage_gb'];
                $os = $row['os'];
                $location = $row['location'];
                $status = $row['status'];
                $assigned_to = $row['assigned_to'];
                $purchase_date = $row['purchase_date'];
                $warranty_expiry = $row['warranty_expiry'];
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

    $sql = "UPDATE computers SET asset_tag=?, type=?, make=?, model=?, serial_number=?, cpu=?, ram_gb=?, storage_type=?, storage_gb=?, os=?, location=?, status=?, assigned_to=?, purchase_date=?, warranty_expiry=?, notes=? WHERE id=?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("sssssssissssssssi", $asset_tag, $type, $make, $model, $serial_number, $cpu, $ram_gb, $storage_type, $storage_gb, $os, $location, $status, $assigned_to, $purchase_date, $warranty_expiry, $notes, $id);
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
    <title>تعديل بيانات جهاز الحاسب</title>
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
            <h2><i class="fas fa-edit"></i> تعديل بيانات جهاز الحاسب</h2>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                <div class="form-group"><label>الرقم التعريفي للأصل</label><input type="text" name="asset_tag" value="<?php echo htmlspecialchars($asset_tag); ?>" required></div>
                <div class="form-group">
                    <label>النوع</label>
                    <select name="type" required>
                        <option value="Desktop" <?php if($type == 'Desktop') echo 'selected'; ?>>كمبيوتر مكتبي</option>
                        <option value="Laptop" <?php if($type == 'Laptop') echo 'selected'; ?>>لابتوب</option>
                        <option value="All-in-One" <?php if($type == 'All-in-One') echo 'selected'; ?>>All-in-One</option>
                    </select>
                </div>
                <div class="form-group"><label>الشركة المصنعة</label><input type="text" name="make" value="<?php echo htmlspecialchars($make); ?>" required></div>
                <div class="form-group"><label>الموديل</label><input type="text" name="model" value="<?php echo htmlspecialchars($model); ?>" required></div>
                <div class="form-group"><label>الرقم التسلسلي</label><input type="text" name="serial_number" value="<?php echo htmlspecialchars($serial_number); ?>" required></div>
                <div class="form-group"><label>معالج (CPU)</label><input type="text" name="cpu" value="<?php echo htmlspecialchars($cpu); ?>" required></div>
                <div class="form-group"><label>الذاكرة العشوائية (RAM GB)</label><input type="number" name="ram_gb" value="<?php echo htmlspecialchars($ram_gb); ?>" required></div>
                <div class="form-group">
                    <label>نوع التخزين</label>
                    <select name="storage_type" required>
                        <option value="SSD" <?php if($storage_type == 'SSD') echo 'selected'; ?>>SSD</option>
                        <option value="HDD" <?php if($storage_type == 'HDD') echo 'selected'; ?>>HDD</option>
                    </select>
                </div>
                <div class="form-group"><label>سعة التخزين (GB)</label><input type="number" name="storage_gb" value="<?php echo htmlspecialchars($storage_gb); ?>" required></div>
                <div class="form-group"><label>نظام التشغيل</label><input type="text" name="os" value="<?php echo htmlspecialchars($os); ?>" required></div>
                <div class="form-group"><label>الموقع</label><input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required></div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" required>
                        <option value="قيد الاستخدام" <?php if($status == 'قيد الاستخدام') echo 'selected'; ?>>قيد الاستخدام</option>
                        <option value="تحت الصيانة" <?php if($status == 'تحت الصيانة') echo 'selected'; ?>>تحت الصيانة</option>
                        <option value="معطل" <?php if($status == 'معطل') echo 'selected'; ?>>معطل</option>
                        <option value="مخزن" <?php if($status == 'مخزن') echo 'selected'; ?>>مخزن</option>
                    </select>
                </div>
                <div class="form-group"><label>مسند إلى</label><input type="text" name="assigned_to" value="<?php echo htmlspecialchars($assigned_to); ?>"></div>
                <div class="form-group"><label>تاريخ الشراء</label><input type="date" name="purchase_date" value="<?php echo htmlspecialchars($purchase_date); ?>"></div>
                <div class="form-group"><label>تاريخ انتهاء الضمان</label><input type="date" name="warranty_expiry" value="<?php echo htmlspecialchars($warranty_expiry); ?>"></div>
                <div class="form-group"><label>ملاحظات</label><textarea name="notes" rows="3"><?php echo htmlspecialchars($notes); ?></textarea></div>
                <input type="submit" class="btn" value="حفظ التغييرات">
                <a href="index.php" class="btn btn-danger">إلغاء</a>
            </form>
        </div>
    </main>
    <script src="../script.js"></script>
</body>
</html>