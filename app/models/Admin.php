<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Admin Model
 * Xử lý xác thực và quản lý admin
 */
class Admin extends Model {
    
    protected $table = 'nhanvien';
    
    /**
     * Đăng nhập admin
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function login($username, $password) {
        $username = $this->escape($username);
        $password = $this->escape($password);
        
        $sql = "SELECT nv.*, vt.TenVaiTro, vt.QuyenHan
                FROM nhanvien nv
                LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                WHERE nv.TenDangNhap = '{$username}' 
                AND nv.MatKhau = '{$password}'
                AND (nv.TrangThai = 'active' OR nv.TrangThai = '1')";
        
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Kiểm tra admin đã đăng nhập chưa
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
    }
    
    /**
     * Kiểm tra quyền admin (Quản trị viên)
     * @return bool
     */
    public function isAdmin() {
        return isset($_SESSION['admin_permission']) && $_SESSION['admin_permission'] === 'ToanQuyen';
    }
    
    /**
     * Lấy thông tin admin hiện tại
     * @return array|null
     */
    public function getCurrentAdmin() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $adminId = intval($_SESSION['admin_id']);
        $sql = "SELECT nv.*, vt.TenVaiTro, vt.QuyenHan
                FROM nhanvien nv
                LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                WHERE nv.MaNV = {$adminId}";
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_role']);
        unset($_SESSION['admin_role_id']);
        unset($_SESSION['admin_permission']);
    }
    
    /**
     * Lấy danh sách tất cả nhân viên
     * @return array
     */
    public function getAllStaff() {
        $sql = "SELECT nv.*, vt.TenVaiTro, vt.QuyenHan
                FROM nhanvien nv
                LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                ORDER BY nv.MaNV DESC";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
    
    /**
     * Thống kê tổng quan
     * @return array
     */
    public function getDashboardStats() {
        $stats = [];
        
        // Tổng sản phẩm
        $result = $this->query("SELECT COUNT(*) as total FROM sanpham");
        $stats['total_products'] = $result->fetch_assoc()['total'];
        
        // Tổng đơn hàng
        $result = $this->query("SELECT COUNT(*) as total FROM hoadon");
        $stats['total_orders'] = $result->fetch_assoc()['total'];
        
        // Tổng khách hàng
        $result = $this->query("SELECT COUNT(*) as total FROM khachhang");
        $stats['total_customers'] = $result->fetch_assoc()['total'];
        
        // Tổng doanh thu
        $result = $this->query("SELECT SUM(TongTien) as total FROM hoadon");
        $row = $result->fetch_assoc();
        $stats['total_revenue'] = $row['total'] ?? 0;
        
        // Đơn hàng gần đây
        // TenKH đã được lưu trực tiếp trong bảng hoadon
        $result = $this->query("SELECT * FROM hoadon 
                                ORDER BY NgayLap DESC 
                                LIMIT 5");
        $stats['recent_orders'] = $result->fetch_all(MYSQLI_ASSOC);
        
        // Sản phẩm sắp hết hàng
        $result = $this->query("SELECT sp.TenSP, bt.KichThuoc, bt.MauSac, bt.TonKho, sp.MaSP
                                FROM sanpham_bienthe bt
                                LEFT JOIN sanpham sp ON bt.MaSP = sp.MaSP
                                WHERE bt.TonKho < 10 AND bt.TonKho > 0
                                ORDER BY bt.TonKho ASC
                                LIMIT 5");
        $stats['low_stock'] = $result->fetch_all(MYSQLI_ASSOC);
        
        return $stats;
    }
}
?>



