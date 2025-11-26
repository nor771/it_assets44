<?php
session_start();
redirect_if_not_logged_in();
require_once "config.php";

// تعيين رأس الاستجابة الصحيح
header('Content-Type: application/json; charset=utf-8');

 $type = isset($_GET['type']) ? $_GET['type'] : '';
 $data = [];

try {
    // التحقق من نوع المعامل
    $allowed_types = ['computers', 'printers', 'landlines'];
    if (!in_array($type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid type parameter']);
        exit;
    }
    
    switch ($type) {
        case 'computers':
            $sql = "SELECT asset_tag, status FROM computers ORDER BY id DESC LIMIT 10";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'asset_tag' => $row['asset_tag'],
                        'status' => $row['status']
                    ];
                }
            }
            break;
            
        case 'printers':
            $sql = "SELECT asset_tag, make, model, status FROM printers ORDER BY id DESC LIMIT 10";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'asset_tag' => $row['asset_tag'],
                        'make' => $row['make'],
                        'model' => $row['model'],
                        'status' => $row['status']
                    ];
                }
            }
            break;
            
        case 'landlines':
            $sql = "SELECT phone_number, status FROM landlines ORDER BY id DESC LIMIT 10";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'phone_number' => $row['phone_number'],
                        'status' => $row['status']
                    ];
                }
            }
            break;
    }
    
    $conn->close();
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // معالجة الأخطاء
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage(),
        'details' => $e->getTraceAsString()
    ]);
}
?>