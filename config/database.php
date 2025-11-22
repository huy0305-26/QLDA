<?php
/**
 * Cấu hình kết nối Database
 */

// Thông tin kết nối database
define('DB_HOST', 'localhost');
define('DB_USER', 'sql_nhom40_itimi');
define('DB_PASS', '49befb3658d24');
define('DB_NAME', 'sql_nhom40_itimi');

// Localhost
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'shopquanao');

// Base URL của website
define('BASE_URL', 'https://nhom40.itimit.id.vn/');
// define('BASE_URL', 'http://localhost/QLDA/');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>