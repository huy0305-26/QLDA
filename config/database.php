<?php
/**
 * Cấu hình kết nối Database
 */

// Thông tin kết nối database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shopquanao');

// Base URL của website
define('BASE_URL', 'http://localhost/QLQA/public/');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>



