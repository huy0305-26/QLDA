<?php
/**
 * Cart Model
 * Xử lý các thao tác liên quan đến giỏ hàng
 */
class Cart {
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     * @param int $variantId ID biến thể sản phẩm
     * @param int $quantity Số lượng
     */
    public function add($variantId, $quantity = 1) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$variantId])) {
            $_SESSION['cart'][$variantId] += $quantity;
        } else {
            $_SESSION['cart'][$variantId] = $quantity;
        }
    }
    
    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     * @param int $variantId
     * @param int $quantity
     */
    public function update($variantId, $quantity) {
        if (isset($_SESSION['cart'][$variantId])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$variantId] = $quantity;
            } else {
                $this->remove($variantId);
            }
        }
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * @param int $variantId
     */
    public function remove($variantId) {
        if (isset($_SESSION['cart'][$variantId])) {
            unset($_SESSION['cart'][$variantId]);
        }
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        unset($_SESSION['cart']);
    }
    
    /**
     * Lấy tất cả items trong giỏ hàng
     * @return array
     */
    public function getItems() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return [];
        }
        
        require_once __DIR__ . '/Product.php';
        $productModel = new Product();
        
        $items = [];
        foreach ($_SESSION['cart'] as $variantId => $quantity) {
            // Lấy thông tin biến thể kèm hình ảnh sản phẩm
            $sql = "SELECT bt.*, sp.TenSP, sp.HinhAnh, dm.TenDM, th.TenTH
                    FROM sanpham_bienthe bt
                    LEFT JOIN sanpham sp ON bt.MaSP = sp.MaSP
                    LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                    LEFT JOIN thuonghieu th ON sp.MaTH = th.MaTH
                    WHERE bt.MaSP_BienThe = " . intval($variantId);
            
            $result = $productModel->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $variant = $result->fetch_assoc();
                $variant['SoLuongMua'] = $quantity;
                $variant['ThanhTien'] = $variant['GiaBan'] * $quantity;
                $items[] = $variant;
            }
        }
        
        return $items;
    }
    
    /**
     * Đếm số lượng items trong giỏ
     * @return int
     */
    public function count() {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        return count($_SESSION['cart']);
    }
    
    /**
     * Tính tổng tiền giỏ hàng
     * @return float
     */
    public function getTotal() {
        $items = $this->getItems();
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['ThanhTien'];
        }
        
        return $total;
    }
    
    /**
     * Kiểm tra giỏ hàng có rỗng không
     * @return bool
     */
    public function isEmpty() {
        return !isset($_SESSION['cart']) || empty($_SESSION['cart']);
    }
}
?>



