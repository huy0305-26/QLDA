<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * VaiTro Model
 * Xử lý các thao tác liên quan đến vai trò
 */
class VaiTro extends Model {
    
    protected $table = 'vaitro';
    
    /**
     * Lấy tất cả vai trò
     * @return array
     */
    public function getAllRoles() {
        $sql = "SELECT * FROM vaitro ORDER BY MaVaiTro";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy thông tin vai trò theo ID
     * @param int $roleId
     * @return array|null
     */
    public function getRoleById($roleId) {
        $sql = "SELECT * FROM vaitro WHERE MaVaiTro = " . intval($roleId);
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Kiểm tra quyền hạn của vai trò
     * @param int $roleId
     * @return string|null
     */
    public function getPermission($roleId) {
        $role = $this->getRoleById($roleId);
        return $role ? $role['QuyenHan'] : null;
    }
}
?>

