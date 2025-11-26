<?php
// تضمين ملف الإعدادات أولاً - سيقوم هذا ببدء الجلسة تلقائياً
require_once "config.php";

// التحقق مما إذا كان المستخدم مسجل دخوله بالفعل
// الآن هذا التحقق آمن لأن الجلسة قد بدأت بالفعل في config.php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// تهيئة المتغيرات لتخزين الأخطاء والبيانات
 $username = $password = "";
 $username_err = $password_err = $login_err = "";

// معالجة البيانات عند إرسال النموذج
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // التحقق من اسم المستخدم
    if(empty(trim($_POST["username"]))){
        $username_err = "الرجاء إدخال اسم المستخدم.";
    } else{
        $username = trim($_POST["username"]);
    }

    // التحقق من كلمة المرور
    if(empty(trim($_POST["password"]))){
        $password_err = "الرجاء إدخال كلمة المرور.";
    } else{
        $password = trim($_POST["password"]);
    }

    // التحقق من عدم وجود أخطاء قبل الاستعلام عن قاعدة البيانات
    if(empty($username_err) && empty($password_err)){

        // إعداد استعلام SELECT آمن (Prepared Statement)
        $sql = "SELECT id, username, password, role, full_name FROM users WHERE username = ?";

        if($stmt = $conn->prepare($sql)){
            // ربط المتغيرات بالعنصر النائب
            $stmt->bind_param("s", $param_username);
            $param_username = $username;

            // تنفيذ الاستعلام
            if($stmt->execute()){
                // تخزين النتيجة للتحقق منها
                $stmt->store_result();

                // التحقق من وجود اسم المستخدم
                if($stmt->num_rows == 1){
                    // ربط النتائج بمتغيرات
                    $stmt->bind_result($id, $db_username, $hashed_password, $user_role, $full_name);
                    if($stmt->fetch()){
                        // التحقق من كلمة المرور
                        if(password_verify($password, $hashed_password)){
                            // كلمة المرور صحيحة، يتم تسجيل الدخول بنجاح
                            
                            // تخزين بيانات في الجلسة
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $db_username;
                            $_SESSION["role"] = $user_role;
                            $_SESSION["full_name"] = $full_name;
                            
                            // التوجيه إلى صفحة لوحة التحكم
                            header("location: index.php");
                        } else{
                            // كلمة المرور غير صحيحة
                            $login_err = "اسم المستخدم أو كلمة المرور غير صحيحة.";
                        }
                    }
                } else{
                    // اسم المستخدم غير موجود
                    $login_err = "اسم المستخدم أو كلمة المرور غير صحيحة.";
                }
            } else{
                echo "حدث خطأ ما. الرجاء المحاولة لاحقاً.";
            }
            // إغلاق الاستعلام
            $stmt->close();
        }
    }
    // إغلاق الاتصال بقاعدة البيانات
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الأصول التقنية</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>تسجيل الدخول</h2>
            <p>الرجاء إدخال بياناتك للوصول إلى نظام إدارة الأصول التقنية.</p>

            <?php 
            // عرض رسالة الخطأ إذا كانت موجودة
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                </div>    
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="دخول">
                </div>
            </form>
        </div>
    </div>
</body>
</html>