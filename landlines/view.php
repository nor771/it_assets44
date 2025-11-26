<?php
require_once "../config.php";

// التحقق من وجود معرف الهاتف
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // جلب بيانات الهاتف
    $sql = "SELECT * FROM landlines WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $landline = $result->fetch_array(MYSQLI_ASSOC);
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
    <title>عرض بيانات الهاتف الأرضي</title>
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
            <div class="actions-bar">
                <h2>عرض بيانات الهاتف الأرضي</h2>
                <div>
                    <a href="edit.php?id=<?php echo $landline['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </div>
            </div>
            
            <div class="detail-container">
                <div class="detail-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="detail-item">
                        <div class="detail-label">رقم الهاتف:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($landline['phone_number']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">القسم:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($landline['department']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الحالة:</div>
                        <div class="detail-value">
                            <span class="status-badge <?php echo $landline['status'] == 'يعمل' ? 'status-working' : 'status-broken'; ?>">
                                <?php echo htmlspecialchars($landline['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">الموقع:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($landline['location']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../script.js"></script>
</body>
</html>