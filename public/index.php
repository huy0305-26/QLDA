<?php
/**
 * Front Controller
 * Điểm vào duy nhất của ứng dụng theo mô hình MVC
 */

// Load config
require_once __DIR__ . '/../config/database.php';

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Tên controller và file
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

// Kiểm tra controller có tồn tại không
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Khởi tạo controller
    $controllerObj = new $controllerName();
    
    // Kiểm tra action có tồn tại không
    if (method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        die("Action không tồn tại: $action");
    }
} else {
    die("Controller không tồn tại: $controllerName");
}
?>




