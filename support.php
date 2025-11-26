<?php session_start(); if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ header("location: login.php"); exit; } ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الدعم الفني</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header"><div class="container"><h1><i class="fas fa-microchip"></i> نظام إدارة الأصول التقنية</h1><nav><a href="#"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></a><a href="logout.php">تسجيل الخروج</a></nav></div></header>
    <main class="container"><div class="form-container" style="text-align: center;"><i class="fas fa-headset" style="font-size: 4rem; color: var(--accent-color); margin-bottom: 1rem;"></i><h2>الدعم الفني</h2><p>للتواصل مع الدعم الفني، يرجى استخدام نافذة المحادثة الذكية الموجودة في أسفل يسار الشاشة.</p></div></main>
    <div id="support-widget"><div id="widget-header"><span><i class="fas fa-comments"></i> الدعم الفني</span><i class="fas fa-chevron-up" id="toggle-widget-icon"></i></div><div id="widget-body"><div id="chat-messages"></div><input type="text" id="chat-input" placeholder="...اكتب رسالتك هنا"></div></div>
    <footer><div class="container"><p>&copy; <?php echo date('Y'); ?> - 2026 كلية التقنية الرقمية بالرياض. جميع الحقوق محفوظة.</p><div class="social-links"><a href="#" title="X (Twitter)" class="social-link"><i class="fab fa-x-twitter"></i></a><a href="#" title="LinkedIn" class="social-link"><i class="fab fa-linkedin-in"></i></a><a href="#" title="GitHub" class="social-link"><i class="fab fa-github"></i></a></div></div></footer>
    <script src="script.js"></script>
</body>
</html>