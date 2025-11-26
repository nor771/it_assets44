<?php
require_once "../config.php";

// التحقق من وجود معرف الجهاز في الرابط
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    
    // جلب بيانات الجهاز من قاعدة البيانات
    $sql = "SELECT * FROM computers WHERE id = ?";
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $computer = $result->fetch_array(MYSQLI_ASSOC);
                // تعريف جميع المتغيرات هنا
                $id = $computer['id'];
                $asset_tag = $computer['asset_tag'];
                $type = $computer['type'];
                $make = $computer['make'];
                $model = $computer['model'];
                $serial_number = $computer['serial_number'];
                $cpu = $computer['cpu'];
                $ram_gb = $computer['ram_gb'];
                $storage_type = $computer['storage_type'];
                $storage_gb = $computer['storage_gb'];
                $os = $computer['os'];
                $location = $computer['location'];
                $status = $computer['status'];
                $assigned_to = $computer['assigned_to'];
                $purchase_date = $computer['purchase_date'];
                $warranty_expiry = $computer['warranty_expiry'];
                $notes = $computer['notes'];
            } else {
                header("location: index.php");
                exit();
            }
            $stmt->close();
        }
    }
} else {
    header("location: index.php");
    exit;
}
 $conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض بيانات جهاز الحاسب</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="../index.php">الرئيسية</a>
                <a href="computers/index.php">قائمة أجهزة الحاسب</a>
                <a href="../logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <div class="actions-bar">
                <h2>عرض بيانات جهاز الحاسب</h2>
                <div>
                    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </div>
            </div>
            
            <div class="detail-container">
                <div class="detail-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="detail-item">
                        <div class="detail-label">الرقم التعريفي:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($asset_tag); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">النوع:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($type); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الشركة المصنعة:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($make . " " . $model); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الرقم التسلسلي:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($serial_number); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الحالة:</div>
                        <div class="detail-value">
                            <span class="status-badge <?php echo ($status === 'قيد الاستخدام' ? 'status-working' : ($status === 'تحت الصيانة' ? 'status-maintenance' : 'status-broken')); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>المواصفات الفنية</h3>
                    <div class="detail-item">
                        <div class="detail-label">معالجهاز:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($cpu); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الذاكرة العشوائية:</div>
                        <div class="detail-value"><?php echo $ram_gb; ?> GB</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">نوع التخزين:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($storage_type); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">سعة التخزين:</div>
                        <div class="detail-value"><?php echo $storage_gb; ?> GB</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">نظام التشغيل:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($os); ?></div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>المعلومات الإدارية</h3>
                    <div class="detail-item">
                        <div class="detail-label">الموقع:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($location); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">مسند إلى:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($assigned_to); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">تاريخ الشراء:</div>
                        <div class="detail-value"><?php echo (!empty($purchase_date) ? date('Y-m-d', strtotime($purchase_date)) : 'غير محدد'); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">تاريخ انتهاء الضمان:</div>
                        <div class="detail-value"><?php echo (!empty($warranty_expiry) ? date('Y-m-d', strtotime($warranty_expiry)) : 'غير محدد'); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">ملاحظات:</div>
                        <div class="detail-value"><?php echo nl2br($notes); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../script.js"></script>
</body>
</html>