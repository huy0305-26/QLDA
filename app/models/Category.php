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
        $sql = "SELECT dm.*, dm_cha.TenDM as TenDMCha 
                FROM danhmuc dm
                LEFT JOIN danhmuc dm_cha ON dm.MaDM_Cha = dm_cha.MaDM
                ORDER BY 
                    COALESCE(dm.MaDM_Cha, dm.MaDM) ASC,
                    CASE WHEN dm.MaDM_Cha IS NULL THEN 0 ELSE 1 END ASC,
                    dm.MaDM ASC";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy danh mục cha (không có MaDM_Cha)
     * @return array
     */
    public function getParentCategories() {
        $sql = "SELECT * FROM danhmuc WHERE MaDM_Cha IS NULL ORDER BY MaDM";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy danh mục con theo ID cha
     * @param int $parentId
     * @return array
     */
    public function getChildCategories($parentId) {
        $sql = "SELECT * FROM danhmuc WHERE MaDM_Cha = " . intval($parentId) . " ORDER BY MaDM";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy cấu trúc danh mục theo dạng cây (cha-con)
     * @return array
     */
    public function getCategoriesTree() {
        $parents = $this->getParentCategories();
        $tree = [];
        
        foreach ($parents as $parent) {
            $parent['children'] = $this->getChildCategories($parent['MaDM']);
            $tree[] = $parent;
        }
        
        return $tree;
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



