<?php
require_once "config.php";

 $user_full_name = $_SESSION["full_name"];
 $my_computers = [];
 $sql = "SELECT id, asset_tag, model, location, status FROM computers WHERE assigned_to = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $user_full_name);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $my_computers[] = $row;
    }
    $stmt->close();
}

 $total_computers = get_asset_count($conn, 'computers');
 $total_printers = get_asset_count($conn, 'printers');
 $total_landlines = get_asset_count($conn, 'landlines');
 $total_assets = $total_computers + $total_printers + $total_landlines;

 $conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المستخدم</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="#"><?php echo htmlspecialchars($user_full_name); ?></a>
                <a href="index.php">الرئيسية</a>
                <a href="logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>لوحة تحكم المستخدم</h2>
            <p>أهلاً بك، <strong><?php echo htmlspecialchars($user_full_name); ?></strong>. إليك نظرة سريعة على أصولك والنظام.</p>
            
            <div class="summary-card">
                <div class="summary-card-value"><?php echo $total_assets; ?></div>
                <div class="summary-card-label">إجمالي الأصول في النظام</div>
            </div>

            <div class="my-assets-section">
                <h3><i class="fas fa-laptop"></i> أجهزة الحاسب المسندة إليك</h3>
                <?php if (count($my_computers) > 0): ?>
                    <table class="employees-table">
                        <thead><tr><th>الرقم التعريفي</th><th>الموديل</th><th>الموقع</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                        <tbody>
                            <?php foreach($my_computers as $computer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($computer['asset_tag']); ?></td>
                                    <td><?php echo htmlspecialchars($computer['model']); ?></td>
                                    <td><?php echo htmlspecialchars($computer['location']); ?></td>
                                    <td><span class="status-badge <?php echo $computer['status'] == 'قيد الاستخدام' ? 'status-working' : ($computer['status'] == 'تحت الصيانة' ? 'status-maintenance' : 'status-broken'); ?>"><?php echo htmlspecialchars($computer['status']); ?></span></td>
                                    <td class='actions'><a href='computers/view.php?id=<?php echo $computer['id']; ?>' class='view' title='عرض'><i class='fas fa-eye'></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-assets-message"><i class="fas fa-inbox"></i><p>لا توجد أجهزة حاسب مسندة إليك حالياً.</p></div>
                <?php endif; ?>
            </div>

            <div class="smart-tools">
                <div class="tool-card" id="quick-solutions-card">
                    <h3><i class="fas fa-bolt"></i> نقرة زر</h3>
                    <p>ابحث عن حلول سريعة للمشاكل الشائعة.</p>
                    <input type="text" id="solutions-search-input" placeholder="...اكتب مشكلتك هنا (مثال: طابعة)">
                    <div id="suggestions-list"></div>
                </div>
                <div class="tool-card" id="ai-chat-card">
                    <h3><i class="fas fa-robot"></i> techo.techo</h3>
                    <p>مساعدك الذكي لحل جميع مشاكلك التقنية.</p>
                </div>
                <div class="tool-card" id="support-trigger-card">
                    <h3><i class="fas fa-headset"></i> طلب دعم فني</h3>
                    <p>هل تواجه مشكلة؟ تواصل مع فريق الدعم الفني.</p>
                </div>
            </div>

            <div class="management-links">
                <h3><i class="fas fa-cogs"></i> روابط سريعة</h3>
                <a href="computers/" class="btn"><i class="fas fa-desktop"></i> إدارة أجهزة الحاسب</a>
                <a href="printers/" class="btn btn-success"><i class="fas fa-print"></i> إدارة الطابعات</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> - 2026 كلية التقنية الرقمية بالرياض. جميع الحقوق محفوظة.</p>
            <div class="social-links">
                <a href="#" title="Twitter" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" title="LinkedIn" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" title="GitHub" class="social-link"><i class="fab fa-github"></i></a>
            </div>
            <div id="support-trigger"><i class="fas fa-comments"></i></div>
        </div>
    </footer>

    <div id="support-chat-modal">
        <div class="support-chat-container">
            <div class="support-chat-header">
                <h2><i class="fas fa-headset"></i> الدعم الفني</h2>
                <i class="fas fa-times" id="close-support-modal"></i>
            </div>
            <div class="support-chat-body"></div>
            <div class="support-chat-footer">
                <form>
                    <input type="text" placeholder="...اكتب رسالتك هنا">
                    <button type="submit" class="btn">إرسال</button>
                </form>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>