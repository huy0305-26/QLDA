<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1><i class="fas fa-credit-card"></i> Thanh toán</h1>

    <?php 
    // Hiển thị lỗi nếu có
    if (isset($_SESSION['checkout_errors'])): 
        $errors = $_SESSION['checkout_errors'];
        unset($_SESSION['checkout_errors']);
    ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="checkout-container">
        <!-- Thông tin giao hàng -->
        <div class="checkout-form">
            <h2><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h2>
            
            <form method="POST" action="index.php?controller=order&action=placeOrder" id="checkoutForm">
                <div class="form-group">
                    <label for="ten_kh"><i class="fas fa-user"></i> Họ và tên <span class="required">*</span></label>
                    <input type="text" 
                           id="ten_kh" 
                           name="ten_kh" 
                           class="form-control" 
                           value="<?php echo isset($_SESSION['checkout_data']['ten_kh']) ? htmlspecialchars($_SESSION['checkout_data']['ten_kh']) : ''; ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="so_dien_thoai"><i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span></label>
                    <input type="tel" 
                           id="so_dien_thoai" 
                           name="so_dien_thoai" 
                           class="form-control" 
                           value="<?php echo isset($_SESSION['checkout_data']['so_dien_thoai']) ? htmlspecialchars($_SESSION['checkout_data']['so_dien_thoai']) : ''; ?>"
                           pattern="[0-9]{10,11}"
                           required>
                    <small class="text-muted">Nhập 10-11 số</small>
                </div>

                <div class="form-group">
                    <label for="dia_chi"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng <span class="required">*</span></label>
                    <textarea id="dia_chi" 
                              name="dia_chi" 
                              class="form-control" 
                              rows="3" 
                              required><?php echo isset($_SESSION['checkout_data']['dia_chi']) ? htmlspecialchars($_SESSION['checkout_data']['dia_chi']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="hinh_thuc_thanh_toan"><i class="fas fa-money-bill-wave"></i> Hình thức thanh toán <span class="required">*</span></label>
                    <select id="hinh_thuc_thanh_toan" name="hinh_thuc_thanh_toan" class="form-control" required>
                        <option value="">-- Chọn hình thức --</option>
                        <option value="Tiền mặt" <?php echo (isset($_SESSION['checkout_data']['hinh_thuc_thanh_toan']) && $_SESSION['checkout_data']['hinh_thuc_thanh_toan'] == 'Tiền mặt') ? 'selected' : ''; ?>>
                            Thanh toán khi nhận hàng (COD)
                        </option>
                        <option value="Chuyển khoản" <?php echo (isset($_SESSION['checkout_data']['hinh_thuc_thanh_toan']) && $_SESSION['checkout_data']['hinh_thuc_thanh_toan'] == 'Chuyển khoản') ? 'selected' : ''; ?>>
                            Chuyển khoản ngân hàng
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ghi_chu"><i class="fas fa-comment"></i> Ghi chú (không bắt buộc)</label>
                    <textarea id="ghi_chu" 
                              name="ghi_chu" 
                              class="form-control" 
                              rows="3" 
                              placeholder="Ghi chú thêm về đơn hàng (thời gian giao hàng, yêu cầu đặc biệt...)"><?php echo isset($_SESSION['checkout_data']['ghi_chu']) ? htmlspecialchars($_SESSION['checkout_data']['ghi_chu']) : ''; ?></textarea>
                </div>

                <div class="checkout-actions">
                    <a href="index.php?controller=cart" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                    <button type="submit" class="btn btn-success btn-large">
                        <i class="fas fa-check-circle"></i> Đặt hàng
                    </button>
                </div>
            </form>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="order-summary">
            <h2><i class="fas fa-shopping-bag"></i> Đơn hàng của bạn</h2>
            
            <div class="order-items">
                <?php foreach ($cartItems as $item): ?>
                <div class="order-item">
                    <div class="order-item-info">
                        <?php if (!empty($item['HinhAnh'])): ?>
                            <img src="uploads/products/<?php echo htmlspecialchars($item['HinhAnh']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['TenSP']); ?>"
                                 class="order-item-image">
                        <?php else: ?>
                            <div class="order-item-no-image">
                                <i class="fas fa-tshirt"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <strong><?php echo htmlspecialchars($item['TenSP']); ?></strong><br>
                            <small>Size: <?php echo htmlspecialchars($item['KichThuoc']); ?> - Màu: <?php echo htmlspecialchars($item['MauSac']); ?></small><br>
                            <small class="text-muted">SL: <?php echo $item['SoLuongMua']; ?> × <?php echo number_format($item['GiaBan'], 0, ',', '.'); ?>đ</small>
                        </div>
                    </div>
                    <div class="order-item-price">
                        <?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?>đ
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="order-total">
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span class="text-success">Miễn phí</span>
                </div>
                <div class="total-row total-final">
                    <strong>Tổng cộng:</strong>
                    <strong class="total-price"><?php echo number_format($total, 0, ',', '.'); ?>đ</strong>
                </div>
            </div>

            <div class="loyalty-info-box">
                <div class="loyalty-header">
                    <i class="fas fa-star"></i>
                    <strong>Tích điểm thành viên</strong>
                </div>
                <p class="loyalty-text">Bạn sẽ nhận được <strong class="highlight-points"><?php echo number_format(floor($total / 1000), 0, ',', '.'); ?> điểm</strong> cho đơn hàng này</p>
                <p class="loyalty-rule"><i class="fas fa-info-circle"></i> 1.000đ = 1 điểm</p>
            </div>
        </div>
    </div>
</div>

<?php 
// Xóa checkout_data sau khi hiển thị
unset($_SESSION['checkout_data']);
?>

<style>
.checkout-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
    margin-top: 30px;
}

.checkout-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.checkout-form h2 {
    margin-bottom: 25px;
    color: #333;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-group label i {
    margin-right: 5px;
    color: #666;
}

.required {
    color: #f04b4c;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #4CAF50;
}

textarea.form-control {
    resize: vertical;
    font-family: inherit;
}

.checkout-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.order-summary {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

.order-summary h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.3rem;
}

.order-items {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-info {
    display: flex;
    gap: 15px;
    flex: 1;
}

.order-item-image {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.order-item-no-image {
    width: 60px;
    height: 80px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    color: #999;
    font-size: 24px;
}

.order-item-price {
    font-weight: bold;
    color: #f04b4c;
}

.order-total {
    border-top: 2px solid #eee;
    padding-top: 15px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.total-final {
    font-size: 18px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid #333;
}

.total-final .total-price {
    color: #f04b4c;
    font-size: 22px;
}

/* Loyalty Info Box */
.loyalty-info-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    color: white;
}

.loyalty-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 16px;
}

.loyalty-header i {
    color: #ffd700;
}

.loyalty-text {
    margin: 10px 0;
    font-size: 15px;
}

.highlight-points {
    color: #ffd700;
    font-size: 18px;
}

.loyalty-rule {
    margin: 10px 0 0;
    font-size: 13px;
    opacity: 0.9;
}

.loyalty-rule i {
    margin-right: 5px;
}

/* Responsive */
@media (max-width: 1024px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
    
    .order-summary {
        position: static;
        max-height: none;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

