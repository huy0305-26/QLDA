<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h1>

    <?php if ($message): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
    <form method="POST" action="">
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Biến thể</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <div class="cart-product">
                                <i class="fas fa-tshirt fa-2x"></i>
                                <div>
                                    <strong><?php echo htmlspecialchars($item['TenSP']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($item['TenTH']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            Size: <?php echo htmlspecialchars($item['KichThuoc']); ?><br>
                            Màu: <?php echo htmlspecialchars($item['MauSac']); ?>
                        </td>
                        <td class="price"><?php echo number_format($item['GiaBan'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <input type="number" 
                                   name="quantity[<?php echo $item['MaSP_BienThe']; ?>]" 
                                   value="<?php echo $item['SoLuongMua']; ?>" 
                                   min="1" 
                                   max="<?php echo $item['TonKho']; ?>"
                                   class="quantity-input">
                            <small class="text-muted">(Còn <?php echo $item['TonKho']; ?>)</small>
                        </td>
                        <td class="price"><strong><?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?>đ</strong></td>
                        <td>
                            <a href="index.php?controller=cart&action=remove&id=<?php echo $item['MaSP_BienThe']; ?>" 
                               class="btn btn-danger btn-small"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="cart-actions">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua hàng
                    </a>
                    <button type="submit" name="update_cart" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Cập nhật giỏ hàng
                    </button>
                    <a href="index.php?controller=cart&action=clear" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                        <i class="fas fa-trash-alt"></i> Xóa giỏ hàng
                    </a>
                </div>

                <div class="cart-total">
                    <h3>Tổng cộng: <span class="total-price"><?php echo number_format($total, 0, ',', '.'); ?>đ</span></h3>
                    <button type="button" class="btn btn-success btn-large" onclick="checkout()">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?php else: ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-cart fa-5x"></i>
        <h2>Giỏ hàng của bạn đang trống</h2>
        <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
        <a href="index.php" class="btn btn-primary">
            <i class="fas fa-shopping-bag"></i> Mua sắm ngay
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function checkout() {
    alert('Chức năng thanh toán đang được phát triển.\n\nGiỏ hàng của bạn có tổng: <?php echo number_format($total, 0, ',', '.'); ?>đ');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>




