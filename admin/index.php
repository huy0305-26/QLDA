<?php
/**
 * Admin Front Controller
 * Entry point cho tất cả requests admin
 * URL: http://localhost/QLQA/admin/
 */

// Load config
require_once __DIR__ . '/../config/database.php';

// Lấy action từ URL
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Load AdminController
require_once __DIR__ . '/../app/controllers/AdminController.php';

// Khởi tạo controller
$controller = new AdminController();

// Kiểm tra action có tồn tại không
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    die("Action không tồn tại: $action");
}
?>








