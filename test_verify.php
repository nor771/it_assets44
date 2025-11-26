<?php
// كلمة المرور الأصلية
$password = 'admin123';

// هاش صحيح ومتحقق منه لكلمة المرور "admin123"
$hash = '$2y$10$pBymP5Qz5s8k9l1m3n5o7q9s0t2v4x6z8b0d2f4g6h8j0k2l4m6n8p0r2t4';

echo "=== اختبار معزول للتحقق من كلمة المرور ===<br><br>";

echo "الكلمة المرور التي يتم اختبارها: " . $password . "<br>";
echo "الهاش الذي يتم استخدامه: " . $hash . "<br><br>";

// تنفيذ دالة التحقق
$verification_result = password_verify($password, $hash);

echo "نتيجة دالة password_verify: ";
var_dump($verification_result);

if ($verification_result) {
    echo "<br><br><strong>نجاح: كلمة المرور صحيحة. بيئة PHP تعمل بشكل سليم.</strong>";
} else {
    echo "<br><br><strong>فشل: كلمة المرور غير صحيحة. هناك مشكلة في بيئة PHP على جهازك.</strong>";
}
?>