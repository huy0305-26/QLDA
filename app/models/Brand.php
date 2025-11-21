<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Brand Model
 * Xử lý các thao tác liên quan đến thương hiệu
 */
class Brand extends Model {
    
    protected $table = 'thuonghieu';
    
    /**
     * Lấy tất cả thương hiệu
     * @return array
     */
    public function getAllBrands() {
        $sql = "SELECT * FROM {$this->table} ORDER BY TenTH";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy thương hiệu theo ID
     * @param int $id
     * @return array|null
     */
    public function getBrandById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE MaTH = " . intval($id);
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>
