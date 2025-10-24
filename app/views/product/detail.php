<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <?php if ($successMessage): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
        <a href="index.php?controller=cart" class="btn-link">Xem giỏ hàng</a>
    </div>
    <?php endif; ?>

    <div class="breadcrumb">
        <a href="index.php">Trang chủ</a> / 
        <a href="index.php?category=<?php echo $product['MaDM']; ?>"><?php echo htmlspecialchars($product['TenDM']); ?></a> / 
        <span><?php echo htmlspecialchars($product['TenSP']); ?></span>
    </div>

    <div class="product-detail">
        <div class="product-detail-left">
            <div class="product-detail-image">
                <?php if (!empty($product['HinhAnh'])): ?>
                    <img src="uploads/products/<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
                         alt="<?php echo htmlspecialchars($product['TenSP']); ?>">
                <?php else: ?>
                    <div class="no-image">
                        <i class="fas fa-tshirt fa-10x"></i>
                        <p>Chưa có hình ảnh</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="product-detail-right">
            <div class="product-detail-info">
                <div class="product-brand-text"><?php echo htmlspecialchars($product['TenTH']); ?></div>
                <h1 class="product-title"><?php echo htmlspecialchars($product['TenSP']); ?></h1>
                
                <?php if (!empty($variants)): ?>
                    <?php 
                    // Lấy giá và % giảm từ biến thể đầu tiên
                    $firstVariant = $variants[0];
                    $hasDiscount = !empty($firstVariant['GiaGoc']) && $firstVariant['GiaGoc'] > $firstVariant['GiaBan'];
                    ?>
                    <div class="product-price-section">
                        <div class="price-row">
                            <span class="price-current" id="detail-price"><?php echo number_format($firstVariant['GiaBan'], 0, ',', '.'); ?>₫</span>
                            <?php if ($hasDiscount): ?>
                                <span class="price-badge">-<?php echo $firstVariant['PhanTramGiam']; ?>%</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($hasDiscount): ?>
                            <div class="price-original" id="detail-price-original"><?php echo number_format($firstVariant['GiaGoc'], 0, ',', '.'); ?>₫</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Danh mục:</span>
                        <span class="meta-value"><?php echo htmlspecialchars($product['TenDM']); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Xuất xứ:</span>
                        <span class="meta-value"><?php echo htmlspecialchars($product['XuatXu']); ?></span>
                    </div>
                </div>

                <?php if (!empty($variants)): ?>
                <form method="POST" action="" class="product-form">
                    <?php
                    // Lấy danh sách size và màu sắc duy nhất
                    $sizes = array_unique(array_column($variants, 'KichThuoc'));
                    $colors = array_unique(array_column($variants, 'MauSac'));
                    ?>
                    
                    <div class="form-group">
                        <label class="form-label">Chọn Size:</label>
                        <div class="size-selector">
                            <?php foreach($sizes as $index => $size): ?>
                            <label class="size-option">
                                <input type="radio" name="selected_size" value="<?php echo htmlspecialchars($size); ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($size); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chọn Màu sắc:</label>
                        <div class="color-selector">
                            <?php foreach($colors as $index => $color): ?>
                            <label class="color-option">
                                <input type="radio" name="selected_color" value="<?php echo htmlspecialchars($color); ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($color); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="variant_id" id="variant-id" value="<?php echo $variants[0]['MaSP_BienThe']; ?>">

                    <div class="form-group">
                        <label class="form-label">Số lượng:</label>
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn" id="qty-minus">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="999" readonly>
                            <button type="button" class="qty-btn" id="qty-plus">+</button>
                        </div>
                        <div id="stock-info" class="stock-info">Còn <?php echo $variants[0]['TonKho']; ?> sản phẩm</div>
                    </div>

                    <button type="submit" name="add_to_cart" class="btn btn-add-cart">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </form>
                <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Sản phẩm tạm thời hết hàng
                </div>
                <?php endif; ?>

                <div class="product-description">
                    <h3>Mô tả sản phẩm</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['MoTa'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dữ liệu biến thể từ PHP
const variants = <?php echo json_encode($variants); ?>;

// Hàm tìm biến thể phù hợp
function findVariant(size, color) {
    return variants.find(v => v.KichThuoc === size && v.MauSac === color);
}

// Hàm cập nhật UI
function updateVariant() {
    const selectedSize = document.querySelector('input[name="selected_size"]:checked')?.value;
    const selectedColor = document.querySelector('input[name="selected_color"]:checked')?.value;
    
    if (selectedSize && selectedColor) {
        const variant = findVariant(selectedSize, selectedColor);
        
        if (variant) {
            // Cập nhật giá
            document.getElementById('detail-price').textContent = parseInt(variant.GiaBan).toLocaleString('vi-VN') + '₫';
            
            // Cập nhật giá gốc nếu có
            const priceOriginal = document.getElementById('detail-price-original');
            if (priceOriginal && variant.GiaGoc && variant.GiaGoc > variant.GiaBan) {
                priceOriginal.textContent = parseInt(variant.GiaGoc).toLocaleString('vi-VN') + '₫';
            }
            
            // Cập nhật tồn kho
            document.getElementById('stock-info').textContent = 'Còn ' + variant.TonKho + ' sản phẩm';
            document.getElementById('quantity').max = variant.TonKho;
            
            // Cập nhật variant ID
            document.getElementById('variant-id').value = variant.MaSP_BienThe;
        }
    }
}

// Xử lý chọn size/màu
document.querySelectorAll('input[name="selected_size"], input[name="selected_color"]').forEach(input => {
    input.addEventListener('change', updateVariant);
});

// Xử lý tăng/giảm số lượng
document.getElementById('qty-minus').addEventListener('click', function() {
    const qtyInput = document.getElementById('quantity');
    const currentVal = parseInt(qtyInput.value);
    if (currentVal > 1) {
        qtyInput.value = currentVal - 1;
    }
});

document.getElementById('qty-plus').addEventListener('click', function() {
    const qtyInput = document.getElementById('quantity');
    const currentVal = parseInt(qtyInput.value);
    const maxVal = parseInt(qtyInput.max);
    if (currentVal < maxVal) {
        qtyInput.value = currentVal + 1;
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>



