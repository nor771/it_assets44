<?php
require_once "../config.php";

// التحقق من وجود معرف الطابعة
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // جلب بيانات الطابعة
    $sql = "SELECT * FROM printers WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $printer = $result->fetch_array(MYSQLI_ASSOC);
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

 $conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض بيانات الطابعة</title>
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
            <div class="actions-bar">
                <h2>عرض بيانات الطابعة</h2>
                <div>
                    <a href="edit.php?id=<?php echo $printer['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </div>
            </div>
            
            <div class="detail-container">
                <div class="detail-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="detail-item">
                        <div class="detail-label">الرقم التعريفي:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['asset_tag']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الشركة المصنعة:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['make']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الموديل:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['model']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الرقم التسلسلي:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['serial_number']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">النوع:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['type']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الحالة:</div>
                        <div class="detail-value">
                            <span class="status-badge <?php echo $printer['status'] == 'يعمل' ? 'status-working' : ($printer['status'] == 'تحت الصيانة' ? 'status-maintenance' : 'status-broken'); ?>">
                                <?php echo htmlspecialchars($printer['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>المعلومات الفنية</h3>
                    <div class="detail-item">
                        <div class="detail-label">عنوان IP:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['ip_address']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الموقع:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['location']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">تاريخ الشراء:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['purchase_date']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">ملاحظات:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($printer['notes']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../script.js"></script>
</body>
</html>