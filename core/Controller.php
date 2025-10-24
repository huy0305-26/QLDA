<?php
/**
 * Base Controller
 * Tất cả các controller sẽ extend từ class này
 */
class Controller {
    
    /**
     * Load view
     * @param string $view Tên view cần load
     * @param array $data Dữ liệu truyền vào view
     */
    protected function view($view, $data = []) {
        // Extract data thành các biến riêng lẻ
        extract($data);
        
        // Đường dẫn đến file view
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View không tồn tại: $view");
        }
    }
    
    /**
     * Load model
     * @param string $model Tên model cần load
     * @return object Instance của model
     */
    protected function model($model) {
        // Đường dẫn đến file model
        $modelPath = __DIR__ . '/../app/models/' . $model . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model không tồn tại: $model");
        }
    }
    
    /**
     * Redirect đến URL khác
     * @param string $url URL cần redirect
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
    
    /**
     * Lấy base URL của website
     * @return string Base URL
     */
    protected function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['SCRIPT_NAME']);
        return $protocol . "://" . $host . $path;
    }
}
?>



