<?php
require_once "config.php";

// جلب الإحصائيات باستخدام الدوال المساعدة
 $row_computers = ['count' => get_asset_count($conn, 'computers')];
 $row_printers = ['count' => get_asset_count($conn, 'printers')];
 $row_landlines = ['count' => get_asset_count($conn, 'landlines')];

// جلب بيانات الحالة باستخدام الدوال المساعدة
 $computers_status_counts = get_asset_status_counts($conn, 'computers');
 $printers_status_counts = get_asset_status_counts($conn, 'printers');
 $landlines_status_counts = get_asset_status_counts($conn, 'landlines');

// جلب بيانات الحالة لجميع الأجهزة للتقرير
 $all_computers = $all_printers = $all_landlines = [];

if (is_admin()) {
    $computers_report_sql = "SELECT id, asset_tag, model, status FROM computers";
    $computers_report_result = $conn->query($computers_report_sql);
    while($row = $computers_report_result->fetch_assoc()) {
        $all_computers[] = $row;
    }
    
    $printers_report_sql = "SELECT id, asset_tag, model, status, location FROM printers";
    $printers_report_result = $conn->query($printers_report_sql);
    while($row = $printers_report_result->fetch_assoc()) {
        $all_printers[] = $row;
    }
    
    $landlines_report_sql = "SELECT id, phone_number, department, status, location FROM landlines";
    $landlines_report_result = $conn->query($landlines_report_sql);
    while($row = $landlines_report_result->fetch_assoc()) {
        $all_landlines[] = $row;
    }
}

 $conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - 2026</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const reportData = {
            computers: <?php echo json_encode($all_computers); ?>,
            printers: <?php echo json_encode($all_printers); ?>,
            landlines: <?php echo json_encode($all_landlines); ?>
        };
    </script>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1>
            <nav>
                <a href="#"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></a>
                <a href="public_dashboard.php">لوحة المستخدم</a>
                <a href="logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (is_admin()): ?>
            <!-- البطاقات الرئيسية -->
            <div class="dashboard-grid">
                <div class="card card-computers">
                    <div class="card-icon"><i class="fas fa-desktop"></i></div>
                    <div class="card-content">
                        <div class="card-count"><?php echo $row_computers['count']; ?></div>
                        <div class="card-title">أجهزة حاسب</div>
                        <div class="asset-status-grid">
                            <div class="asset-status-item">
                                <span class="status-indicator working"></span>
                                <span>يعمل: <?php echo $computers_status_counts['working']; ?></span>
                            </div>
                            <div class="asset-status-item">
                                <span class="status-indicator broken"></span>
                                <span>معطلة: <?php echo $computers_status_counts['broken']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-printers">
                    <div class="card-icon"><i class="fas fa-print"></i></div>
                    <div class="card-content">
                        <div class="card-count"><?php echo $row_printers['count']; ?></div>
                        <div class="card-title">طابعات</div>
                        <div class="asset-status-grid">
                            <div class="asset-status-item">
                                <span class="status-indicator working"></span>
                                <span>تعمل: <?php echo $printers_status_counts['working']; ?></span>
                            </div>
                            <div class="asset-status-item">
                                <span class="status-indicator broken"></span>
                                <span>معطلة: <?php echo $printers_status_counts['broken']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-landlines">
                    <div class="card-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="card-content">
                        <div class="card-count"><?php echo $row_landlines['count']; ?></div>
                        <div class="card-title">هواتف أرضية</div>
                        <div class="asset-status-grid">
                            <div class="asset-status-item">
                                <span class="status-indicator working"></span>
                                <span>يعمل: <?php echo $landlines_status_counts['working']; ?></span>
                            </div>
                            <div class="asset-status-item">
                                <span class="status-indicator broken"></span>
                                <span>معطل: <?php echo $landlines_status_counts['broken']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          
            <!-- قسم الأدوات الذكية -->
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
                <div class="tool-card" id="reports-card">
                    <h3><i class="fas fa-file-alt"></i> طباعة التقارير</h3>
                    <p>اعرض حالة جميع الأجهزة واطبع تقريراً شاملاً.</p>
                </div>
                <div class="tool-card" id="pending-tasks-card">
                    <h3><i class="fas fa-tasks"></i> المهام المعلقة</h3>
                    <p>3 مهام تحتاج إلى متابعة.</p>
                </div>
            </div>

            <!-- قسم الإدارة السريعة مع الرسم البياني -->
            <div class="admin-content-wrapper">
                <div class="chart-box">
                    <h3>توزيع الأصول</h3>
                    <canvas id="assetChart"></canvas>
                </div>
                <div class="form-container">
                    <h3>الإدارة السريعة</h3>
                    <a href="computers/add.php" class="btn"><i class="fas fa-plus-circle"></i> إضافة جهاز حاسب</a>
                    <a href="printers/add.php" class="btn btn-success"><i class="fas fa-plus-circle"></i> إضافة طابعة</a>
                    <a href="landlines/add.php" class="btn btn-warning"><i class="fas fa-plus-circle"></i> إضافة هاتف أرضي</a>
                </div>
            </div>
        <?php else: ?>
            <div class="form-container">
                <h2>لوحة تحكم المستخدم</h2>
                <p>أهلاً بك في لوحة التحكم. يمكنك إدارة الأجهزة والطابعات من الروابط التالية:</p>
                <a href="computers/" class="btn"><i class="fas fa-desktop"></i> إدارة أجهزة الحاسب</a>
                <a href="printers/" class="btn btn-success"><i class="fas fa-print"></i> إدارة الطابعات</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- نافذة الشات الجديدة للدعم الفني -->
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

    <!-- Footer مع أيقونة الدعم الفني -->
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

    <!-- نافذة التقارير المنبثقة -->
    <div id="reports-modal">
        <div class="reports-container">
            <div class="reports-header">
                <h3>تقرير حالة الأصول</h3>
                <i class="fas fa-times" id="close-reports-modal"></i>
            </div>
            <div class="reports-body">
                <div class="report-section">
                    <h4>أجهزة الحاسب</h4>
                    <table class="reports-table">
                        <thead><tr><th>الرقم التعريفي</th><th>الموديل</th><th>الحالة</th></tr></thead>
                        <tbody id="computers-report-tbody"></tbody>
                    </table>
                </div>
                <div class="report-section">
                    <h4>الطابعات</h4>
                    <table class="reports-table">
                        <thead><tr><th>الرقم التعريفي</th><th>الموديل</th><th>الحالة</th><th>الموقع</th></tr></thead>
                        <tbody id="printers-report-tbody"></tbody>
                    </table>
                </div>
                <div class="report-section">
                    <h4>الهواتف الأرضية</h4>
                    <table class="reports-table">
                        <thead><tr><th>رقم الهاتف</th><th>القسم</th><th>الحالة</th><th>الموقع</th></tr></thead>
                        <tbody id="landlines-report-tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="reports-footer">
                <button id="print-report-btn" class="btn"><i class="fas fa-print"></i> طباعة التقرير</button>
            </div>
        </div>
    </div>

    <?php if (is_admin()): ?>
    <script>
        const ctxAsset = document.getElementById('assetChart').getContext('2d');
        new Chart(ctxAsset, {
            type: 'doughnut', 
            data: {
                labels: ['أجهزة حاسب', 'طابعات', 'هواتف أرضية'],
                datasets: [{
                    data: [<?php echo $row_computers['count']; ?>, <?php echo $row_printers['count']; ?>, <?php echo $row_landlines['count']; ?>],
                    backgroundColor: ['#00BCCC', '#1FBAB3', '#4F8DA7'],
                    borderColor: ['#fff', '#fff', '#fff'],
                    borderWidth: 2
                }]
            }, 
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Cairo',
                                size: 11
                            },
                            color: '#404040'
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            family: 'Cairo'
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
    <script src="script.js"></script>
</body>
</html>