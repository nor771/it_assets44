<?php
require_once "../config.php";

// إضافة التصفح والبحث
 $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
 $records_per_page = 10;
 $offset = ($page - 1) * $records_per_page;
 $search = isset($_GET['search']) ? trim($_GET['search']) : '';

// بناء الاستعلام مع البحث
 $search_condition = $search ? "WHERE phone_number LIKE '%$search%' OR department LIKE '%$search%' OR location LIKE '%$search%'" : "";
 $sql = "SELECT * FROM landlines $search_condition ORDER BY id DESC LIMIT ?, ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("ii", $offset, $records_per_page);
 $stmt->execute();
 $result = $stmt->get_result();

// استعلام للحصول على العدد الإجمالي للسجلات
 $total_sql = "SELECT COUNT(*) as total FROM landlines $search_condition";
 $total_result = $conn->query($total_sql);
 $total_records = $total_result->fetch_assoc()['total'];
 $total_pages = ceil($total_records / $records_per_page);
 $conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الهواتف الأرضية</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="../index.php">الرئيسية</a>
                <a href="../logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <div class="actions-bar">
                <h2>إدارة الهواتف الأرضية</h2>
                <a href="add.php" class="btn" style="background-color: var(--success-color);">
                    <i class="fas fa-plus-circle"></i> إضافة هاتف جديد
                </a>
            </div>
            
            <form class="search-form" method="GET" action="index.php">
                <input type="text" name="search" placeholder="بحث عن هاتف..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn"><i class="fas fa-search"></i></button>
            </form>
            
            <table class="employees-table">
                <thead>
                    <tr>
                        <th>رقم الهاتف</th>
                        <th>القسم</th>
                        <th>الحالة</th>
                        <th>الموقع</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td>
                                    <?php
                                    // تحويل الحالة إلى أحرف صغيرة للمقارنة الموثوقة
                                    $status_class = '';
                                    $status_text = htmlspecialchars($row['status']);
                                    
                                    if (strtolower($row['status']) == 'يعمل') {
                                        $status_class = 'status-working';
                                    } elseif (strtolower($row['status']) == 'معطل') {
                                        $status_class = 'status-broken';
                                    }
                                    
                                    echo '<span class="status-badge ' . $status_class . '">' . $status_text . '</span>';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td class="actions">
                                    <a href='view.php?id=<?php echo $row['id']; ?>' class='view' title='عرض'><i class='fas fa-eye'></i></a>
                                    <a href='edit.php?id=<?php echo $row['id']; ?>' class='edit' title='تعديل'><i class='fas fa-edit'></i></a>
                                    <a href='delete.php?id=<?php echo $row['id']; ?>' class='delete delete-link' title='حذف'><i class='fas fa-trash'></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan='5'>لا توجد هواتف أرضية مسجلة</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- ترقيم الصفحات -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="index.php?page=<?php echo $page - 1; ?><?php echo $search ? "&search=" . urlencode($search) : ""; ?>" class="btn-pagination">
                        <i class="fas fa-chevron-right"></i> السابق
                    </a>
                <?php endif; ?>
                
                <span class="page-info">صفحة <?php echo $page; ?> من <?php echo $total_pages; ?></span>
                
                <?php if ($page < $total_pages): ?>
                    <a href="index.php?page=<?php echo $page + 1; ?><?php echo $search ? "&search=" . urlencode($search) : ""; ?>" class="btn-pagination">
                        التالي <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <script src="../script.js"></script>
</body>
</html>