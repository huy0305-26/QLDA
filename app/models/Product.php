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
     * @param int $limit Giới hạn số sản phẩm (0 = tất cả)
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getAllProducts($categoryId = 0, $limit = 0, $offset = 0) {
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
        
        if ($limit > 0) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Đếm tổng số sản phẩm theo danh mục
     * @param int $categoryId Lọc theo danh mục (0 = tất cả)
     * @return int
     */
    public function countProducts($categoryId = 0) {
        $sql = "SELECT COUNT(DISTINCT sp.MaSP) as total
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP
                WHERE EXISTS (
                    SELECT 1 FROM sanpham_bienthe bt2 
                    WHERE bt2.MaSP = sp.MaSP AND bt2.TonKho > 0
                )";
        
        if ($categoryId > 0) {
            $sql .= " AND (sp.MaDM = " . intval($categoryId) . " OR dm.MaDM_Cha = " . intval($categoryId) . ")";
        }
        
        $result = $this->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return intval($row['total']);
        }
        return 0;
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
     * Lấy danh sách thương hiệu dựa trên từ khóa tìm kiếm
     * @param string $keyword
     * @return array
     */
    public function getBrandsByKeyword($keyword) {
        $keyword = $this->escape($keyword);
        $sql = "SELECT DISTINCT th.* 
                FROM thuonghieu th
                JOIN sanpham sp ON th.MaTH = sp.MaTH
                WHERE sp.TenSP LIKE '%{$keyword}%' 
                OR sp.MoTa LIKE '%{$keyword}%'
                OR th.TenTH LIKE '%{$keyword}%'
                ORDER BY th.TenTH";
        
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Lấy danh sách danh mục dựa trên từ khóa tìm kiếm
     * @param string $keyword
     * @return array
     */
    public function getCategoriesByKeyword($keyword) {
        $keyword = $this->escape($keyword);
        $sql = "SELECT DISTINCT dm.* 
                FROM danhmuc dm
                JOIN sanpham sp ON dm.MaDM = sp.MaDM
                WHERE sp.TenSP LIKE '%{$keyword}%' 
                OR sp.MoTa LIKE '%{$keyword}%'
                OR dm.TenDM LIKE '%{$keyword}%'
                ORDER BY dm.TenDM";
        
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Lấy danh sách xuất xứ dựa trên từ khóa tìm kiếm
     * @param string $keyword
     * @return array
     */
    public function getOriginsByKeyword($keyword) {
        $keyword = $this->escape($keyword);
        $sql = "SELECT DISTINCT sp.XuatXu 
                FROM sanpham sp
                WHERE (sp.TenSP LIKE '%{$keyword}%' 
                OR sp.MoTa LIKE '%{$keyword}%')
                AND sp.XuatXu IS NOT NULL 
                AND sp.XuatXu != ''
                ORDER BY sp.XuatXu";
        
        $result = $this->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row['XuatXu'];
            }
            return $data;
        }
        return [];
    }

    /**
     * Tìm kiếm sản phẩm
     * @param string $keyword
     * @param int $brandId
     * @param int $categoryId
     * @param int $minPrice
     * @param int $maxPrice
     * @param string $origin
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function search($keyword, $brandId = 0, $categoryId = 0, $minPrice = 0, $maxPrice = 0, $origin = '', $limit = 0, $offset = 0) {
        $keyword = $this->escape($keyword);
        $origin = $this->escape($origin);
        
        $sql = "SELECT sp.MaSP, sp.TenSP, sp.MoTa, sp.HinhAnh, dm.TenDM, th.TenTH, sp.XuatXu,
                MIN(bt.GiaBan) as GiaThapNhat,
                MAX(bt.GiaBan) as GiaCaoNhat,
                MIN(bt.GiaGoc) as GiaGocThapNhat,
                MAX(bt.GiaGoc) as GiaGocCaoNhat,
                SUM(bt.TonKho) as TongTonKho
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP
                WHERE (sp.TenSP LIKE '%{$keyword}%'
                OR sp.MoTa LIKE '%{$keyword}%'
                OR dm.TenDM LIKE '%{$keyword}%'
                OR th.TenTH LIKE '%{$keyword}%')";
        
        if ($brandId > 0) {
            $sql .= " AND sp.MaTH = " . intval($brandId);
        }
        
        if ($categoryId > 0) {
            $sql .= " AND (sp.MaDM = " . intval($categoryId) . " OR dm.MaDM_Cha = " . intval($categoryId) . ")";
        }

        if (!empty($origin)) {
            $sql .= " AND sp.XuatXu = '{$origin}'";
        }
        
        $sql .= " GROUP BY sp.MaSP
                 HAVING TongTonKho > 0";

        if ($minPrice > 0) {
            $sql .= " AND GiaThapNhat >= " . intval($minPrice);
        }

        if ($maxPrice > 0) {
            $sql .= " AND GiaThapNhat <= " . intval($maxPrice);
        }
                 
        $sql .= " ORDER BY sp.MaSP DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Đếm số lượng kết quả tìm kiếm (để phân trang)
     * @param string $keyword
     * @param int $brandId
     * @param int $categoryId
     * @param int $minPrice
     * @param int $maxPrice
     * @param string $origin
     * @return int
     */
    public function countSearch($keyword, $brandId = 0, $categoryId = 0, $minPrice = 0, $maxPrice = 0, $origin = '') {
        $keyword = $this->escape($keyword);
        $origin = $this->escape($origin);
        
        // Sử dụng subquery để đếm số lượng group
        $sql = "SELECT COUNT(*) as total FROM (
                SELECT sp.MaSP,
                MIN(bt.GiaBan) as GiaThapNhat,
                SUM(bt.TonKho) as TongTonKho
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                LEFT JOIN sanpham_bienthe bt ON sp.MaSP = bt.MaSP
                WHERE (sp.TenSP LIKE '%{$keyword}%'
                OR sp.MoTa LIKE '%{$keyword}%'
                OR dm.TenDM LIKE '%{$keyword}%'
                OR th.TenTH LIKE '%{$keyword}%')";
        
        if ($brandId > 0) {
            $sql .= " AND sp.MaTH = " . intval($brandId);
        }
        
        if ($categoryId > 0) {
            $sql .= " AND (sp.MaDM = " . intval($categoryId) . " OR dm.MaDM_Cha = " . intval($categoryId) . ")";
        }

        if (!empty($origin)) {
            $sql .= " AND sp.XuatXu = '{$origin}'";
        }
        
        $sql .= " GROUP BY sp.MaSP
                 HAVING TongTonKho > 0";

        if ($minPrice > 0) {
            $sql .= " AND GiaThapNhat >= " . intval($minPrice);
        }

        if ($maxPrice > 0) {
            $sql .= " AND GiaThapNhat <= " . intval($maxPrice);
        }
                 
        $sql .= ") as subquery";
        
        $result = $this->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return intval($row['total']);
        }
        return 0;
    }
}
?>



