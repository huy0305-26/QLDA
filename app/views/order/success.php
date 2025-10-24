<?php 
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/Customer.php';
?>

<div class="container">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Đặt hàng thành công!</h1>
        <p class="success-message">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
        
        <div class="order-info-card">
            <h2><i class="fas fa-receipt"></i> Thông tin đơn hàng</h2>
            
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Mã đơn hàng:</span>
                    <span class="detail-value"><strong>#<?php echo str_pad($order['MaHD'], 6, '0', STR_PAD_LEFT); ?></strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Ngày đặt:</span>
                    <span class="detail-value"><?php echo date('d/m/Y', strtotime($order['NgayLap'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Tên người nhận:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['TenKH']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Số điện thoại:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['SoDienThoai']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Địa chỉ giao hàng:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['DiaChi']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Hình thức thanh toán:</span>
                    <span class="detail-value">
                        <span class="payment-badge <?php echo $order['HinhThucThanhToan'] == 'Tiền mặt' ? 'badge-cod' : 'badge-bank'; ?>">
                            <?php echo $order['HinhThucThanhToan'] == 'Tiền mặt' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng'; ?>
                        </span>
                    </span>
                </div>
                
                <?php if (!empty($order['GhiChu'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Ghi chú:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['GhiChu']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <span class="detail-label">Trạng thái:</span>
                    <span class="detail-value">
                        <span class="status-badge status-processing"><?php echo htmlspecialchars($order['TrangThai']); ?></span>
                    </span>
                </div>
            </div>
        </div>

        <?php if ($loyaltyInfo && $customerInfo): ?>
        <div class="loyalty-card">
            <h2><i class="fas fa-star"></i> Tích điểm thành viên</h2>
            
            <?php if ($loyaltyInfo['is_new_customer']): ?>
            <div class="welcome-message">
                <i class="fas fa-gift"></i>
                <p><strong>Chào mừng bạn đến với Shop Quần Áo!</strong></p>
                <p>Tài khoản thành viên đã được tạo tự động với số điện thoại: <strong><?php echo htmlspecialchars($order['SoDienThoai']); ?></strong></p>
            </div>
            <?php endif; ?>
            
            <div class="loyalty-info-grid">
                <div class="loyalty-item">
                    <div class="loyalty-icon earned">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="loyalty-details">
                        <div class="loyalty-label">Điểm tích lũy lần này</div>
                        <div class="loyalty-value earned-points">+<?php echo number_format($loyaltyInfo['points_earned'], 0, ',', '.'); ?> điểm</div>
                        <div class="loyalty-note">(<?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ = <?php echo number_format($loyaltyInfo['points_earned'], 0, ',', '.'); ?> điểm)</div>
                    </div>
                </div>
                
                <div class="loyalty-item">
                    <div class="loyalty-icon total">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="loyalty-details">
                        <div class="loyalty-label">Tổng điểm hiện tại</div>
                        <div class="loyalty-value total-points"><?php echo number_format($customerInfo['DiemTichLuy'], 0, ',', '.'); ?> điểm</div>
                    </div>
                </div>
                
                <div class="loyalty-item">
                    <div class="loyalty-icon tier">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="loyalty-details">
                        <div class="loyalty-label">Hạng thành viên</div>
                        <div class="loyalty-value">
                            <span class="tier-badge" style="background: <?php echo Customer::getMembershipTierColor($customerInfo['HangThanhVien']); ?>">
                                <?php echo Customer::getMembershipTierName($customerInfo['HangThanhVien']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="loyalty-note-box">
                <i class="fas fa-info-circle"></i>
                <p><strong>Quy tắc tích điểm:</strong> 1.000đ = 1 điểm</p>
                <p><strong>Hạng thành viên:</strong></p>
                <ul>
                    <li>Thường: 0-499 điểm</li>
                    <li>Bạc: 500-1.999 điểm</li>
                    <li>Vàng: 2.000-4.999 điểm</li>
                    <li>Kim Cương: 5.000+ điểm</li>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <div class="order-items-card">
            <h2><i class="fas fa-box"></i> Sản phẩm đã đặt</h2>
            
            <div class="ordered-items">
                <?php foreach ($orderDetails as $item): ?>
                <div class="ordered-item">
                    <div class="ordered-item-info">
                        <strong><?php echo htmlspecialchars($item['TenSP']); ?></strong>
                        <small><?php echo htmlspecialchars($item['TenTH']); ?></small>
                        <small>Size: <?php echo htmlspecialchars($item['KichThuoc']); ?> - Màu: <?php echo htmlspecialchars($item['MauSac']); ?></small>
                    </div>
                    <div class="ordered-item-quantity">
                        SL: <?php echo $item['SoLuong']; ?>
                    </div>
                    <div class="ordered-item-price">
                        <?php echo number_format($item['DonGia'], 0, ',', '.'); ?>đ
                    </div>
                    <div class="ordered-item-total">
                        <?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?>đ
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="order-total-summary">
                <div class="total-summary-row">
                    <span>Tạm tính:</span>
                    <span><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</span>
                </div>
                <div class="total-summary-row">
                    <span>Phí vận chuyển:</span>
                    <span class="text-success">Miễn phí</span>
                </div>
                <div class="total-summary-row final-total">
                    <strong>Tổng cộng:</strong>
                    <strong class="total-amount"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</strong>
                </div>
            </div>
        </div>

        <?php if ($order['HinhThucThanhToan'] == 'Chuyển khoản'): ?>
        <div class="payment-info-card">
            <h2><i class="fas fa-university"></i> Thông tin chuyển khoản</h2>
            <div class="bank-info">
                <p><strong>Ngân hàng:</strong> Vietcombank - Chi nhánh TP.HCM</p>
                <p><strong>Số tài khoản:</strong> 0123456789</p>
                <p><strong>Chủ tài khoản:</strong> SHOP QUẦN ÁO</p>
                <p><strong>Số tiền:</strong> <span class="highlight-amount"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</span></p>
                <p><strong>Nội dung:</strong> <span class="highlight-content">DH <?php echo str_pad($order['MaHD'], 6, '0', STR_PAD_LEFT); ?> <?php echo htmlspecialchars($order['SoDienThoai']); ?></span></p>
                <p class="note"><i class="fas fa-info-circle"></i> Vui lòng chuyển khoản đúng nội dung để đơn hàng được xử lý nhanh chóng.</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="success-actions">
            <a href="index.php" class="btn btn-primary btn-large">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="index.php" class="btn btn-success btn-large">
                <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 40px auto;
    text-align: center;
}

.success-icon {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.success-container h1 {
    color: #4CAF50;
    margin-bottom: 10px;
    font-size: 2rem;
}

.success-message {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.order-info-card,
.order-items-card,
.payment-info-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    text-align: left;
}

.order-info-card h2,
.order-items-card h2,
.payment-info-card h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.3rem;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.order-details {
    margin-top: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #666;
    font-weight: 500;
}

.detail-value {
    color: #333;
    text-align: right;
}

.payment-badge {
    padding: 5px 12px;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 500;
}

.badge-cod {
    background: #fff3cd;
    color: #856404;
}

.badge-bank {
    background: #d1ecf1;
    color: #0c5460;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 500;
}

.status-processing {
    background: #fff3cd;
    color: #856404;
}

.ordered-items {
    margin-top: 20px;
}

.ordered-item {
    display: grid;
    grid-template-columns: 2fr 80px 120px 120px;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    align-items: center;
}

.ordered-item:last-child {
    border-bottom: none;
}

.ordered-item-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.ordered-item-info strong {
    color: #333;
}

.ordered-item-info small {
    color: #666;
    font-size: 13px;
}

.ordered-item-quantity {
    text-align: center;
    color: #666;
}

.ordered-item-price {
    text-align: right;
    color: #666;
}

.ordered-item-total {
    text-align: right;
    font-weight: bold;
    color: #f04b4c;
}

.order-total-summary {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.total-summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.final-total {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid #333;
    font-size: 1.2rem;
}

.total-amount {
    color: #f04b4c;
    font-size: 1.5rem;
}

.bank-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    border-left: 4px solid #4CAF50;
}

.bank-info p {
    margin: 10px 0;
    color: #333;
}

.highlight-amount {
    color: #f04b4c;
    font-weight: bold;
    font-size: 1.1rem;
}

.highlight-content {
    color: #4CAF50;
    font-weight: bold;
    background: #fff;
    padding: 5px 10px;
    border-radius: 3px;
}

.bank-info .note {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed #ddd;
    color: #666;
    font-size: 14px;
}

.success-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

/* Loyalty Card Styles */
.loyalty-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    color: white;
}

.loyalty-card h2 {
    color: white;
    border-bottom: 2px solid rgba(255,255,255,0.3);
}

.welcome-message {
    background: rgba(255,255,255,0.2);
    padding: 15px 20px;
    border-radius: 5px;
    margin: 20px 0;
    text-align: center;
}

.welcome-message i {
    font-size: 30px;
    margin-bottom: 10px;
    display: block;
    color: #ffd700;
}

.welcome-message p {
    margin: 5px 0;
    color: white;
}

.loyalty-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 25px 0;
}

.loyalty-item {
    background: rgba(255,255,255,0.15);
    padding: 20px;
    border-radius: 8px;
    display: flex;
    gap: 15px;
    align-items: center;
    backdrop-filter: blur(10px);
}

.loyalty-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.loyalty-icon.earned {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.loyalty-icon.total {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.loyalty-icon.tier {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #333;
}

.loyalty-details {
    flex: 1;
}

.loyalty-label {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.loyalty-value {
    font-size: 22px;
    font-weight: bold;
    line-height: 1.2;
}

.earned-points {
    color: #ffd700;
}

.total-points {
    color: #00f2fe;
}

.loyalty-note {
    font-size: 11px;
    opacity: 0.8;
    margin-top: 3px;
}

.tier-badge {
    display: inline-block;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 15px;
    font-weight: bold;
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.loyalty-note-box {
    background: rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 5px;
    border-left: 4px solid #ffd700;
}

.loyalty-note-box i {
    color: #ffd700;
    margin-right: 8px;
}

.loyalty-note-box p {
    margin: 10px 0;
    font-size: 14px;
}

.loyalty-note-box ul {
    margin: 10px 0 0 25px;
    padding: 0;
}

.loyalty-note-box li {
    margin: 5px 0;
    font-size: 13px;
}

/* Responsive */
@media (max-width: 768px) {
    .ordered-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .ordered-item-quantity,
    .ordered-item-price,
    .ordered-item-total {
        text-align: left;
    }
    
    .success-actions {
        flex-direction: column;
    }
    
    .loyalty-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
