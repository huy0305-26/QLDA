<?php
require_once __DIR__ . '/../../core/Model.php';

/**
 * Customer Model
 * Xử lý khách hàng và tích điểm
 */
class Customer extends Model {
    
    /**
     * Tìm khách hàng theo số điện thoại
     */
    public function findByPhone($phone) {
        $phone = $this->db->real_escape_string($phone);
        $sql = "SELECT * FROM khachhang WHERE SoDienThoai = '{$phone}'";
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Tạo khách hàng mới
     */
    public function create($customerData) {
        $hoTen = $this->db->real_escape_string($customerData['ho_ten']);
        $soDienThoai = $this->db->real_escape_string($customerData['so_dien_thoai']);
        $email = isset($customerData['email']) ? $this->db->real_escape_string($customerData['email']) : '';
        $diaChi = isset($customerData['dia_chi']) ? $this->db->real_escape_string($customerData['dia_chi']) : '';
        $hangThanhVien = 'Thuong'; // Mặc định là hạng Thường
        $diemTichLuy = 0;
        
        // Lấy mã khách hàng tiếp theo
        $maKH = $this->getNextId('khachhang', 'MaKH');
        
        $sql = "INSERT INTO khachhang (MaKH, HoTen, SoDienThoai, Email, DiaChi, HangThanhVien, DiemTichLuy)
                VALUES ({$maKH}, '{$hoTen}', '{$soDienThoai}', '{$email}', '{$diaChi}', '{$hangThanhVien}', {$diemTichLuy})";
        
        if ($this->db->query($sql)) {
            return $maKH;
        }
        
        return false;
    }
    
    /**
     * Cập nhật thông tin khách hàng
     */
    public function update($customerId, $customerData) {
        $hoTen = $this->db->real_escape_string($customerData['ho_ten']);
        $email = isset($customerData['email']) ? $this->db->real_escape_string($customerData['email']) : '';
        $diaChi = isset($customerData['dia_chi']) ? $this->db->real_escape_string($customerData['dia_chi']) : '';
        
        $sql = "UPDATE khachhang 
                SET HoTen = '{$hoTen}',
                    Email = '{$email}',
                    DiaChi = '{$diaChi}'
                WHERE MaKH = " . intval($customerId);
        
        return $this->db->query($sql);
    }
    
    /**
     * Thêm điểm tích lũy
     * Quy tắc: 1000đ = 1 điểm
     */
    public function addPoints($customerId, $orderTotal) {
        // Tính điểm: 1000đ = 1 điểm
        $points = floor($orderTotal / 1000);
        
        if ($points <= 0) {
            return true; // Không có điểm để thêm
        }
        
        $sql = "UPDATE khachhang 
                SET DiemTichLuy = DiemTichLuy + {$points}
                WHERE MaKH = " . intval($customerId);
        
        if ($this->db->query($sql)) {
            // Kiểm tra và cập nhật hạng thành viên
            $this->updateMembershipTier($customerId);
            return $points;
        }
        
        return false;
    }
    
    /**
     * Cập nhật hạng thành viên dựa vào điểm tích lũy
     * Thuong: 0-499 điểm
     * Bac: 500-1999 điểm
     * Vang: 2000-4999 điểm
     * KimCuong: 5000+ điểm
     */
    private function updateMembershipTier($customerId) {
        $customer = $this->getById($customerId);
        
        if (!$customer) {
            return false;
        }
        
        $points = intval($customer['DiemTichLuy']);
        $newTier = 'Thuong';
        
        if ($points >= 5000) {
            $newTier = 'KimCuong';
        } elseif ($points >= 2000) {
            $newTier = 'Vang';
        } elseif ($points >= 500) {
            $newTier = 'Bac';
        }
        
        // Chỉ cập nhật nếu hạng thay đổi
        if ($newTier != $customer['HangThanhVien']) {
            $sql = "UPDATE khachhang 
                    SET HangThanhVien = '{$newTier}'
                    WHERE MaKH = " . intval($customerId);
            
            return $this->db->query($sql);
        }
        
        return true;
    }
    
    /**
     * Lấy thông tin khách hàng theo ID
     */
    public function getById($customerId) {
        $sql = "SELECT * FROM khachhang WHERE MaKH = " . intval($customerId);
        $result = $this->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy tất cả khách hàng
     */
    public function getAll() {
        $sql = "SELECT * FROM khachhang ORDER BY DiemTichLuy DESC";
        $result = $this->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    /**
     * Lấy tên hạng thành viên bằng tiếng Việt
     */
    public static function getMembershipTierName($tier) {
        $tiers = [
            'Thuong' => 'Thường',
            'Bac' => 'Bạc',
            'Vang' => 'Vàng',
            'KimCuong' => 'Kim Cương'
        ];
        
        return isset($tiers[$tier]) ? $tiers[$tier] : 'Thường';
    }
    
    /**
     * Lấy màu badge cho hạng thành viên
     */
    public static function getMembershipTierColor($tier) {
        $colors = [
            'Thuong' => '#999',
            'Bac' => '#c0c0c0',
            'Vang' => '#ffd700',
            'KimCuong' => '#00bcd4'
        ];
        
        return isset($colors[$tier]) ? $colors[$tier] : '#999';
    }
}
?>

