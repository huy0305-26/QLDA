<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Order Model
 * Xử lý đơn hàng
 */
class Order extends Model {
    
    /**
     * Tạo đơn hàng mới
     */
    public function createOrder($orderData) {
        $tenKH = $this->db->real_escape_string($orderData['ten_kh']);
        $soDienThoai = $this->db->real_escape_string($orderData['so_dien_thoai']);
        $diaChi = $this->db->real_escape_string($orderData['dia_chi']);
        $ghiChu = isset($orderData['ghi_chu']) ? $this->db->real_escape_string($orderData['ghi_chu']) : '';
        $hinhThucThanhToan = $this->db->real_escape_string($orderData['hinh_thuc_thanh_toan']);
        $tongTien = floatval($orderData['tong_tien']);
        $ngayLap = date('Y-m-d');
        
        $sql = "INSERT INTO hoadon (NgayLap, TongTien, HinhThucThanhToan, TenKH, SoDienThoai, DiaChi, GhiChu, TrangThai)
                VALUES ('{$ngayLap}', {$tongTien}, '{$hinhThucThanhToan}', '{$tenKH}', '{$soDienThoai}', '{$diaChi}', '{$ghiChu}', 'Đang xử lý')";
        
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    /**
     * Tạo chi tiết đơn hàng
     */
    public function createOrderDetails($orderId, $cartItems) {
        $success = true;
        
        foreach ($cartItems as $item) {
            $maSPBienThe = intval($item['MaSP_BienThe']);
            $soLuong = intval($item['SoLuongMua']);
            $donGia = floatval($item['GiaBan']);
            $thanhTien = floatval($item['ThanhTien']);
            
            $sql = "INSERT INTO chitiethoadon (MaHD, MaSP_BienThe, SoLuong, DonGia, ThanhTien)
                    VALUES ({$orderId}, {$maSPBienThe}, {$soLuong}, {$donGia}, {$thanhTien})";
            
            if (!$this->db->query($sql)) {
                $success = false;
                break;
            }
            
            // Trừ tồn kho
            $this->updateStock($maSPBienThe, $soLuong);
        }
        
        return $success;
    }
    
    /**
     * Cập nhật tồn kho
     */
    private function updateStock($variantId, $quantity) {
        $sql = "UPDATE sanpham_bienthe 
                SET TonKho = TonKho - {$quantity}
                WHERE MaSP_BienThe = {$variantId}";
        
        return $this->db->query($sql);
    }
    
    /**
     * Lấy thông tin đơn hàng
     */
    public function getOrderById($orderId) {
        $sql = "SELECT * FROM hoadon WHERE MaHD = " . intval($orderId);
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy chi tiết đơn hàng
     */
    public function getOrderDetails($orderId) {
        $sql = "SELECT ct.*, sp.TenSP, bt.KichThuoc, bt.MauSac, th.TenTH
                FROM chitiethoadon ct
                JOIN sanpham_bienthe bt ON ct.MaSP_BienThe = bt.MaSP_BienThe
                JOIN sanpham sp ON bt.MaSP = sp.MaSP
                JOIN thuonghieu th ON sp.MaTH = th.MaTH
                WHERE ct.MaHD = " . intval($orderId) . "
                ORDER BY ct.MaCTHD";
        
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Lấy tất cả đơn hàng (cho admin)
     */
    public function getAllOrders() {
        $sql = "SELECT * FROM hoadon ORDER BY NgayLap DESC, MaHD DESC";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($orderId, $status) {
        $status = $this->db->real_escape_string($status);
        $sql = "UPDATE hoadon SET TrangThai = '{$status}' WHERE MaHD = " . intval($orderId);
        
        return $this->db->query($sql);
    }
    
    /**
     * Cập nhật MaKH cho đơn hàng
     */
    public function updateCustomerId($orderId, $customerId) {
        $sql = "UPDATE hoadon SET MaKH = " . intval($customerId) . " WHERE MaHD = " . intval($orderId);
        
        return $this->db->query($sql);
    }
}
?>

