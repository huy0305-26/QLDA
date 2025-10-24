<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h1>

    <?php if ($message): ?>
    <div class="alert alert-success" id="cartAlert">
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
                                <?php if (!empty($item['HinhAnh'])): ?>
                                    <img src="uploads/products/<?php echo htmlspecialchars($item['HinhAnh']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['TenSP']); ?>"
                                         class="cart-product-image">
                                <?php else: ?>
                                    <div class="cart-product-no-image">
                                        <i class="fas fa-tshirt"></i>
                                    </div>
                                <?php endif; ?>
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
                                   class="quantity-input"
                                   data-price="<?php echo $item['GiaBan']; ?>"
                                   data-variant-id="<?php echo $item['MaSP_BienThe']; ?>"
                                   onchange="updatePrice(this)">
                            <small class="text-muted">(Còn <?php echo $item['TonKho']; ?>)</small>
                        </td>
                        <td class="price item-total" data-variant-id="<?php echo $item['MaSP_BienThe']; ?>">
                            <strong><?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?>đ</strong>
                        </td>
                        <td class="text-center">
                            <a href="index.php?controller=cart&action=remove&id=<?php echo $item['MaSP_BienThe']; ?>" 
                               class="btn btn-danger btn-small"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm:\n\n<?php echo htmlspecialchars($item['TenSP']); ?>\nSize: <?php echo htmlspecialchars($item['KichThuoc']); ?> - Màu: <?php echo htmlspecialchars($item['MauSac']); ?>\n\nkhỏi giỏ hàng?')"
                               title="Xóa sản phẩm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="cart-total">
                    <h3>Tổng cộng: <span class="total-price" id="totalPrice"><?php echo number_format($total, 0, ',', '.'); ?>đ</span></h3>
                    <a href="index.php?controller=order&action=checkout" class="btn btn-success btn-large">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </a>
                    <a href="index.php" class="btn btn-secondary btn-large" style="margin-top: 10px;">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua hàng
                    </a>
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
// Tự động ẩn thông báo sau 5 giây
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('cartAlert');
    if (alert) {
        // Thêm animation fade out
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            // Xóa hoàn toàn sau khi animation kết thúc
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 5000); // 5 giây
    }
});

// Cập nhật giá khi thay đổi số lượng
function updatePrice(input) {
    const quantity = parseInt(input.value) || 1;
    const price = parseInt(input.dataset.price);
    const variantId = input.dataset.variantId;
    const max = parseInt(input.max);
    
    // Validate số lượng
    if (quantity < 1) {
        input.value = 1;
        return updatePrice(input);
    }
    
    if (quantity > max) {
        input.value = max;
        alert('Số lượng tối đa: ' + max);
        return updatePrice(input);
    }
    
    // Tính thành tiền cho sản phẩm này
    const itemTotal = quantity * price;
    
    // Cập nhật hiển thị thành tiền
    const itemTotalElement = document.querySelector('.item-total[data-variant-id="' + variantId + '"] strong');
    if (itemTotalElement) {
        itemTotalElement.textContent = formatNumber(itemTotal) + 'đ';
    }
    
    // Cập nhật tổng tiền giỏ hàng
    updateCartTotal();
    
    // Tự động lưu vào session (AJAX)
    saveQuantityToSession(variantId, quantity);
}

// Lưu số lượng vào session qua AJAX
function saveQuantityToSession(variantId, quantity) {
    const formData = new FormData();
    formData.append('update_quantity', '1');
    formData.append('variant_id', variantId);
    formData.append('quantity', quantity);
    
    fetch('index.php?controller=cart&action=updateQuantity', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Đã lưu số lượng:', quantity);
            // Cập nhật số lượng giỏ hàng ở header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount && data.cart_count !== undefined) {
                cartCount.textContent = data.cart_count;
            }
        } else {
            console.error('Lỗi:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Cập nhật tổng tiền giỏ hàng
function updateCartTotal() {
    let total = 0;
    
    // Duyệt qua tất cả sản phẩm
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        const quantity = parseInt(input.value) || 1;
        const price = parseInt(input.dataset.price);
        total += quantity * price;
    });
    
    // Cập nhật hiển thị tổng tiền
    const totalElement = document.getElementById('totalPrice');
    if (totalElement) {
        totalElement.textContent = formatNumber(total) + 'đ';
    }
}

// Format số tiền
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>




