<?php
require_once "../config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    $sql = "DELETE FROM landlines WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            header("location: index.php");
            exit();
        } else {
            echo "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
        }
        $stmt->close();
    }
    $conn->close();
} else {
    header("location: index.php");
    exit();
}
?>