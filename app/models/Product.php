<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Product Model
 * Xử lý các thao tác liên quan đến sản phẩm
 */
class Product extends Model {
    
    protected $table = 'sanpham';
    
    /**
     * Lấy tất cả sản phẩm với thông tin đầy đủ (cho người dùng - chỉ sản phẩm còn hàng)
     * @param int $categoryId Lọc theo danh mục (0 = tất cả)
     * @return array
     */
    public function getAllProducts($categoryId = 0) {
        $sql = "SELECT sp.MaSP, sp.TenSP, sp.MoTa, sp.HinhAnh, dm.TenDM, th.TenTH, sp.XuatXu,
                MIN(bt.GiaBan) as GiaThapNhat, 
                MAX(bt.GiaBan) as GiaCaoNhat,
                MIN(bt.GiaGoc) as GiaGocThapNhat,
                MAX(bt.GiaGoc) as GiaGocCaoNhat,
                GROUP_CONCAT(DISTINCT bt.MauSac SEPARATOR ', ') as MauSac,
                GROUP_CONCAT(DISTINCT bt.KichThuoc SEPARATOR ', ') as KichThuoc,
                SUM(bt.TonKho) as TongTonKho
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP";
        
        if ($categoryId > 0) {
            // Lọc theo danh mục hiện tại HOẶC danh mục có danh mục cha là categoryId
            $sql .= " WHERE (sp.MaDM = " . intval($categoryId) . " OR dm.MaDM_Cha = " . intval($categoryId) . ")";
        }
        
        $sql .= " GROUP BY sp.MaSP
                 HAVING TongTonKho > 0
                 ORDER BY sp.MaSP DESC";
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy tất cả sản phẩm cho Admin (bao gồm cả sản phẩm hết hàng và chưa có biến thể)
     * @param int $categoryId Lọc theo danh mục (0 = tất cả)
     * @return array
     */
    public function getAllProductsForAdmin($categoryId = 0) {
        $sql = "SELECT sp.MaSP, sp.TenSP, sp.MoTa, sp.HinhAnh, dm.TenDM, th.TenTH, sp.XuatXu,
                MIN(bt.GiaBan) as GiaThapNhat, 
                MAX(bt.GiaBan) as GiaCaoNhat,
                MIN(bt.GiaGoc) as GiaGocThapNhat,
                MAX(bt.GiaGoc) as GiaGocCaoNhat,
                GROUP_CONCAT(DISTINCT bt.MauSac SEPARATOR ', ') as MauSac,
                GROUP_CONCAT(DISTINCT bt.KichThuoc SEPARATOR ', ') as KichThuoc,
                COALESCE(SUM(bt.TonKho), 0) as TongTonKho
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP";
        
        if ($categoryId > 0) {
            // Lọc theo danh mục hiện tại HOẶC danh mục có danh mục cha là categoryId
            $sql .= " WHERE (sp.MaDM = " . intval($categoryId) . " OR dm.MaDM_Cha = " . intval($categoryId) . ")";
        }
        
        $sql .= " GROUP BY sp.MaSP
                 ORDER BY sp.MaSP DESC";
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy thông tin chi tiết sản phẩm
     * @param int $productId
     * @return array|null
     */
    public function getProductDetail($productId) {
        $sql = "SELECT sp.*, dm.TenDM, th.TenTH
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                WHERE sp.MaSP = " . intval($productId);
        
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Lấy các biến thể của sản phẩm (cho admin - bao gồm cả hết hàng)
     * @param int $productId
     * @param bool $onlyInStock Chỉ lấy biến thể còn hàng (false = tất cả)
     * @return array
     */
    public function getProductVariants($productId, $onlyInStock = false) {
        $sql = "SELECT *, 
                CASE 
                    WHEN GiaGoc > 0 AND GiaGoc > GiaBan THEN ROUND(((GiaGoc - GiaBan) / GiaGoc) * 100, 0)
                    ELSE 0 
                END as PhanTramGiam
                FROM sanpham_bienthe 
                WHERE MaSP = " . intval($productId);
        
        if ($onlyInStock) {
            $sql .= " AND TonKho > 0";
        }
        
        $sql .= " ORDER BY KichThuoc, MauSac";
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Lấy thông tin biến thể theo ID
     * @param int $variantId
     * @return array|null
     */
    public function getVariantById($variantId) {
        $sql = "SELECT bt.*, sp.TenSP, dm.TenDM, th.TenTH
                FROM sanpham_bienthe bt
                LEFT JOIN sanpham sp ON bt.MaSP = sp.MaSP
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                WHERE bt.MaSP_BienThe = " . intval($variantId);
        
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Tìm kiếm sản phẩm
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        $keyword = $this->escape($keyword);
        
        $sql = "SELECT sp.MaSP, sp.TenSP, sp.MoTa, dm.TenDM, th.TenTH,
                MIN(bt.GiaBan) as GiaThapNhat
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP
                WHERE sp.TenSP LIKE '%{$keyword}%' 
                OR sp.MoTa LIKE '%{$keyword}%'
                OR dm.TenDM LIKE '%{$keyword}%'
                OR th.TenTH LIKE '%{$keyword}%'
                GROUP BY sp.MaSP";
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}
?>



