<?php
/**
 * Base Model
 * Tất cả các model sẽ extend từ class này
 */
class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        // Kết nối database
        $this->db = $this->connectDB();
    }
    
    /**
     * Kết nối database
     * @return mysqli Database connection
     */
    private function connectDB() {
        require_once __DIR__ . '/../config/database.php';
        
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Kết nối database thất bại: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        
        return $conn;
    }
    
    /**
     * Lấy tất cả records
     * @return array
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->db->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Tìm record theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Thực thi query tùy chỉnh
     * @param string $sql
     * @return mysqli_result
     */
    public function query($sql) {
        return $this->db->query($sql);
    }
    
    /**
     * Escape string để tránh SQL injection
     * @param string $str
     * @return string
     */
    public function escape($str) {
        return $this->db->real_escape_string($str);
    }
    
    /**
     * Lấy database connection
     * @return mysqli
     */
    public function getConnection() {
        return $this->db;
    }
    
    /**
     * Lấy ID của record vừa insert
     * @return int
     */
    public function getLastInsertId() {
        return $this->db->insert_id;
    }
    
    /**
     * Lấy mã tiếp theo dựa trên mã lớn nhất hiện có
     * @param string $table Tên bảng
     * @param string $column Tên cột mã (VD: MaDM, MaSP, MaKH, ...)
     * @return int Mã tiếp theo
     */
    public function getNextId($table, $column) {
        $sql = "SELECT MAX({$column}) as max_id FROM {$table}";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $maxId = $row['max_id'];
            
            // Nếu chưa có bản ghi nào, trả về 1
            return ($maxId === null) ? 1 : ($maxId + 1);
        }
        
        return 1;
    }
    
    /**
     * Đóng kết nối database
     */
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>



