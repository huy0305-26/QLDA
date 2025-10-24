<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Category Model
 * Xử lý các thao tác liên quan đến danh mục
 */
class Category extends Model {
    
    protected $table = 'danhmuc';
    
    /**
     * Lấy tất cả danh mục
     * @return array
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM danhmuc ORDER BY MaDM";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy thông tin danh mục theo ID
     * @param int $categoryId
     * @return array|null
     */
    public function getCategoryById($categoryId) {
        $sql = "SELECT * FROM danhmuc WHERE MaDM = " . intval($categoryId);
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Đếm số sản phẩm trong danh mục
     * @param int $categoryId
     * @return int
     */
    public function countProducts($categoryId) {
        $sql = "SELECT COUNT(*) as total 
                FROM sanpham 
                WHERE MaDM = " . intval($categoryId);
        $result = $this->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }
}
?>



